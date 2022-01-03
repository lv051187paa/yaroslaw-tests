const KEY_CODE_MAP = {
    ENTER: 13,
    ESC: 27,
};

const NO_QUESTION_TEXT = 'Питання відсутні';
const NO_OPTIONS_TEXT = 'Варіанти відповіді відсутні';

const EDITABLE_TYPES = {
    TEXT: 'text',
    QUESTION_TYPES: 'options-question-types',
};

const EDITOR_TOGGLER_TYPE_LIST = {
    TEST: 'test',
    QUESTION: 'question',
};

/* ======================= WP ajax request method ======================= */

const submitAjaxRequest = (testData, onSuccess, onError) => {
    return $.ajax({
        type: 'POST',
        url: window.ajaxurl,
        data: {
            action: testData.action,
            ...testData,
        },
        success: function (response) {
            onSuccess && onSuccess(response);
        },
        error: function (error) {
            onError && onError(error);
        },
    });
};

/* ======================= Dynamic elements ======================= */

const getDynamicInput = (value) =>
    `<input class="form-control form-control-sm" type="text" value="${value}">`;

const getDynamicSelect = (options) => {
    return function (value) {
        let optionsHtml = '';
        options.forEach(option =>
            optionsHtml += `<option ${value === option.name ? 'Selected' : ''}  value="${option.name}">
                ${option.name}
            </option>`);
        return `<select class="custom-select">
          ${optionsHtml}
        </select>`;
    };
};

/* ======================= Options for question list table template ======================= */

const getOptionItem = (option) => `<div class="single-answer d-inline-flex mr-2" data-id="${option.id}">
                                    <span class="badge badge-primary option-text rounded-left">${option.text}</span>
                                    <span class="badge badge-primary rounded-right remove-answer">&times;</span>
                                </div>`;

/* ======================= Elements interactions ======================= */

$('.test-activation').change(function () {
    editTestHandler($(this));
});

$('.remove-test').click(function () {
    removeTestHandler($(this));
});

$('.question-activation').change(function () {
    editQuestionHandler($(this));
});

$('.remove-question').click(function () {
    removeQuestionHandler($(this));
});

$('.add-option').click(function () {
    const testId = $('table').data('test-id');
    optionHandler($(this), testId);
});

$('.option-text').click(function () {
    const testId = $('table').data('test-id');
    optionHandler($(this), testId);
});

$('.remove-answer').click(function () {
    const testId = $('table').data('test-id');
    removeOptionHandler($(this), testId);
});

$('table td .edit-handler').click(function () {
    const handlerType = $(this).closest('table').data('list-type');
    const $editableItem = $(this).closest('td').find('.editable');
    editTableItemHandler(handlerType, $editableItem);
});

if (!$('#question-types').length) {
    submitAjaxRequest({
            action: 'get_question_types',
        },
        (response) => {
            $('body').append(`<div id="question-types" class="d-none">${JSON.stringify(response.data)}</div>`);
        });
}

/* ======================= Row editor ======================= */

const editTableItemHandler = (handlerType, $target) => {
    const $editableList = $('.table-editable');
    const isEditMode = $editableList.data('editing');
    const isElementEditMode = $target.data('editing');
    if (isElementEditMode) {
        return;
    }
    $('td[data-editing]').each(function () {
        const isEditMode = $(this).data('editing');
        if (isEditMode) {
            $(this).data({ editing: false });
            editorToggler(handlerType, $(this));
        }
    });
    $target.data({ editing: !isElementEditMode });
    if (isEditMode) {
        $editableList.data({
            editing: !isElementEditMode,
        });
    }

    $editableList.data({ editing: true });
    editorToggler(handlerType, $target);
};

const editorToggler = (handlerType, $target) => {
    const isEditMode = $target.data('editing');
    if (!isEditMode) {
        const newVal = $target.find('#editable-cell').val();
        $target.html(newVal);
        $(document).off('keyup');
        return $target.data({ editing: false });
    }

    const editableType = $target.data('edit-type');
    const currentValue = $target.text().trim();
    const getEditableElement = editableElementFactory(editableType);
    $target.removeClass('editable');
    const $nestedElements = $target.children().first();

    $target.html(getEditableElement(currentValue));
    $target.children().first().attr('id', 'editable-cell');
    $target.find('#editable-cell').focus();

    $(document).keyup(function (e) {
        if (e.which === KEY_CODE_MAP.ENTER) {
            const newVal = $target.find('#editable-cell').val();
            insertValueToEditableElement($target, $nestedElements, newVal)
            $target.data({ editing: false });
            $(document).off('keyup');
            $target.addClass('editable');
            editorRequestFactory(handlerType, $target);
        }

        if (e.which === KEY_CODE_MAP.ESC) {
            insertValueToEditableElement($target, $nestedElements, currentValue)
            $target.data({ editing: false });
            $(document).off('keyup');
            $target.addClass('editable');
        }
    });
};

const insertValueToEditableElement = ($targetCell, $nestedElements, value) => {
    if($nestedElements.length) {
        $targetCell.html($nestedElements);
        $targetCell.children().first().text(value)
    } else {
        $targetCell.html(value);
    }
}

const editorRequestFactory = (type, $target) => {
    switch (type) {
        case EDITOR_TOGGLER_TYPE_LIST.TEST:
            return editTestHandler($target);
        case EDITOR_TOGGLER_TYPE_LIST.QUESTION:
            return editQuestionHandler($target);
    }
};

const editableElementFactory = (editableType) => {
    switch (editableType) {
        case EDITABLE_TYPES.TEXT:
            return getDynamicInput;
        case EDITABLE_TYPES.QUESTION_TYPES:
            const $questionTypesElement = $('#question-types');
            return questionTypesSelectBuilder($questionTypesElement);
        default:
            return getDynamicInput;
    }
};

/* ======================= tests list page ======================= */

const editTestHandler = ($target) => {
    const $currentTest = $target.closest('tr');
    const id = $currentTest.data('id');
    const $testNameCell = $currentTest.find('.test-name div');
    const $testDescriptionCell = $currentTest.find('.test-description div');

    const isTestActive = $currentTest.find('.test-activation').prop('checked');

    const testNameValue = $testNameCell.text().trim();
    const testDescriptionValue = $testDescriptionCell.text().trim();
    submitAjaxRequest({
        action: 'edit_test',
        testId: id,
        testName: testNameValue,
        testDescription: testDescriptionValue,
        isTestActive: Number(isTestActive),
    });
};

const removeTestHandler = ($target) => {
    const $testContainer = $target.closest('table');
    const $testRow = $target.closest('tr');
    const testId = $testRow.data('id');
    submitAjaxRequest({
            action: 'archive_test',
            id: Number(testId),
        },
        () => {
            $testRow.remove();
            const $tests = $testContainer.find('tbody tr');
            if (!$tests.length) {
                $testContainer.remove();
            }
        });
};

/* ======================= tests questions page ======================= */

const removeQuestionHandler = ($target) => {
    const $questionContainer = $target.closest('tbody');
    const $questionRow = $target.closest('tr');
    const questionId = $questionRow.data('id');
    submitAjaxRequest({
            action: 'archive_question',
            questionId: Number(questionId),
        },
        () => {
            $questionRow.remove();
            const $questions = $questionContainer.find('tr');
            if (!$questions.length) {
                const noQuestionsHtml = `<tr class="no-data">
                        <td colspan="4">
                            ${NO_QUESTION_TEXT}
                        </td>
                    </tr>`;
                $questionContainer.html(noQuestionsHtml);
            }
        });
};

const editQuestionHandler = ($target) => {
    const questionRow = $target.closest('tr');
    const questionId = questionRow.data('id');
    const questionTypeListStr = $('#question-types').text();
    const questionTypes = JSON.parse(questionTypeListStr);
    const questionName = questionRow.find('.question-name div').text().trim();
    const questionTypeName = questionRow.find('.question-type div').text().trim();
    const isQuestionActive = questionRow.find('.question-activation').prop('checked');
    const questionType = questionTypes.find(questionType => questionType.type_name === questionTypeName);

    submitAjaxRequest({
        action: 'edit_question',
        questionId,
        questionName,
        questionTypeId: questionType.id,
        isQuestionActive: Number(isQuestionActive),
    });
};

const questionTypesSelectBuilder = ($questionTypesElement) => {
    if ($questionTypesElement) {
        const questionTypeListStr = $questionTypesElement.text().replace(/type_name/g, 'name');
        const questionTypes = JSON.parse(questionTypeListStr);
        return getDynamicSelect(questionTypes);
    }

    return getDynamicSelect([]);
};

/* ======================= test option handlers ======================= */

const openOptionModal = () => {
    $('#option-modal').modal('show');
};

const optionHandler = ($target) => {
    $('#option-form')[0].reset();
    const optionId = $target.closest('.single-answer').data('id');
    $('#option-modal-title').text(optionId ? 'Редагування' : 'Нове питання');
    const questionId = $target.closest('tr').data('id');
    optionId && optionEditHandler(optionId);
    openOptionModal();
    $('#save-option').click(function () {
        const optionText = $('#form-option-name').val();
        const optionValue = $('#form-option-value').val();
        const action = optionId ? 'edit_option' : 'save_option';
        const optionMainData = {
            action,
            optionText,
            optionValue: Number(optionValue),
        };
        const testData = optionId ?
            {
                ...optionMainData,
                optionId: Number(optionId),
            } :
            {
                ...optionMainData,
                questionId: Number(questionId),
            };
        submitAjaxRequest(testData,
            (response) => {
                $('#option-modal').modal('hide');
                $('#save-option').off('click');
                const $questionRow = $(`#questions-list tr[data-id=${Number(questionId)}]`);
                if (action === 'save_option') {
                    saveOptionHandler(response.data, optionText, $questionRow);
                } else {
                    $optionItem = $questionRow.find(`.single-answer[data-id=${Number(optionId)}]`);
                    $optionItem.find('.option-text').text(optionText);
                }

                const testId = $('#questions-list').data('test-id');
                $('.option-text').click(function () {
                    optionHandler($(this), testId);
                });

                $('.remove-answer').click(function () {
                    removeOptionHandler($(this), testId);
                });
            },
        );
    });
};

const saveOptionHandler = (optionId, optionText, $questionRow) => {
    const newOption = {
        id: optionId,
        text: optionText,
    };

    const $newOptionElement = getOptionItem(newOption);
    const $optionsListContainer = $questionRow.find('.answers .card-body');
    const $optionsList = $optionsListContainer.find('.single-answer');
    if ($optionsList.length) {
        $optionsListContainer.append($newOptionElement);
    } else {
        $optionsListContainer.html($newOptionElement);
    }
};

const removeOptionHandler = ($target) => {
    const $optionsContainer = $target.closest('.answers .card-body');
    const $optionItem = $target.closest('.single-answer');
    const optionId = $optionItem.data('id');
    submitAjaxRequest({
            action: 'remove_option',
            optionId: Number(optionId),
        },
        () => {
            $optionItem.remove();
            const $options = $optionsContainer.find('.single-answer');
            if (!$options.length) {
                $optionsContainer.html(NO_OPTIONS_TEXT);
            }
        });
};

const optionEditHandler = (optionId) => {
    submitAjaxRequest({
            action: 'option_details',
            optionId: Number(optionId),
        },
        (response) => {
            $('#form-option-name').val(response.data.option_text);
            $('#form-option-value').val(response.data.option_value);
        });
};