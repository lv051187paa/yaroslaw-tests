$('.test-actions .yaroslaw-tests-edit-test').click(function () {
    const id = $(this).closest('tr').data('id')
    const testNameCell = $(this).closest('tr').find('.test-name');
    const testDescriptionCell = $(this).closest('tr').find('.test-description');

    const { isTestActive } = getTestData($(this).closest('tr'));

    $(this).toggleClass('yaroslaw-tests-edit-test');
    $(this).toggleClass('yaroslaw-tests-save-test');

    if ($(this).hasClass('yaroslaw-tests-edit-test')) {
        $(this).find('.material-icons').text('edit');
        const testNameValue = testNameCell.find('input').val();
        const testDescriptionValue = testDescriptionCell.find('input').val();
        submitTestEdit({
                testId: id,
                testName: testNameValue,
                testDescription: testDescriptionValue,
                isTestActive,
            },
            () => {
                testNameCell.html(testNameValue)
                testDescriptionCell.html(testDescriptionValue)
            })
    } else {
        $(this).find('.material-icons').text('save');
        const testNameValue = testNameCell.text().trim();
        const testNameInput = getTestInput(testNameValue);
        const testDescriptionValue = testDescriptionCell.text().trim();
        const testDescriptionInput = getTestInput(testDescriptionValue);
        testNameCell.html(testNameInput);
        testDescriptionCell.html(testDescriptionInput);
        testNameCell.find('input').focus();
    }
})

$('.test-activation').change(function () {
    const id = $(this).closest('tr').data('id');
    const isTestActive = $(this).prop('checked');
    const { testName, testDescription } = getTestData($(this).closest('tr'));
    submitTestEdit({
        testId: id,
        isTestActive: Number(isTestActive),
        testName,
        testDescription,
    })
})

const getTestData = (testTableRow) => {
    const testName = testTableRow.find('.test-name').text().trim();
    const testDescription = testTableRow.find('.test-description').text().trim();
    const isTestActive = testTableRow.find('.test-activation').prop('checked');

    return {
        testName,
        testDescription,
        isTestActive: Number(isTestActive),
    }
}

const getTestInput = (value) =>
    `<input class="form-control form-control-sm" type="text" value="${value}">`

const submitTestEdit = (testData, onSuccess, onError) => {
    $.ajax({
        type: "POST",
        url: window.ajaxurl,
        data: {
            action: 'edit_test',
            ...testData,
        },
        success: function (response) {
            onSuccess && onSuccess(response)
        },
        error: function (error) {
            onError && onError(error)
        }
    });
}