/* ============================= heplers ==================================*/

const getQuestionItemTitle = (targetElement) => $(targetElement).closest('.question-list__item').find('.question-list__item-title');

/* ============================= AJAX submit util ==================================*/

const submitAjaxRequest = (data, onSuccess, onError) => {
    return $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: {
            action: data.action,
            ...data,
        },
        success: function (response) {
            onSuccess && onSuccess(response);
        },
        error: function (error) {
            onError && onError(error);
        },
    });
};

/* ============================= Test main form validation ==================================*/

const ckecboxGroups = $('.question-list__item');
const validationRuleList = Array.from(ckecboxGroups).reduce((checkboxGroups, currentGroup) => {
    const checkboxes = $(currentGroup).find('input').first().attr('name');
    if (checkboxes) {
        checkboxGroups.rules[checkboxes] = { required: true };
        checkboxGroups.messages[checkboxes] = { required: 'Ви не надали відповіді на всі запитання' };
    }

    return checkboxGroups;
}, { rules: {}, messages: {} });

$('#test-form').validate({
    rules: {
        ...validationRuleList.rules,
    },
    messages: {
        ...validationRuleList.messages,
    },
    errorPlacement: function (error, element) {
        $('#form_error').html(error);
    },
    submitHandler: function (form) {
        alert('Form Submited');
        return false;
    },
    highlight: function (element, errorClass, validClass) {
        getQuestionItemTitle(element).removeClass(validClass);
        getQuestionItemTitle(element).addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
        getQuestionItemTitle(element).addClass(validClass);
        getQuestionItemTitle(element).removeClass(errorClass);
    },
});

/* ============================= Save each question handler ==================================*/

$('.question-list__item input').change(function () {
    const questionId = $(this).attr('name').split('_')[1];
    const questionType = $(this).attr('type');
    const testId = $(this).closest('form').data('test-id');
    const data = {
        questionId,
        optionValue: $(this).val(),
        type: questionType,
        testId
    };
    submitAjaxRequest({
        ...data,
        action: 'add_answer',
    },
        (res) => console.log(res),
        (err) => console.log(err)
    )
});