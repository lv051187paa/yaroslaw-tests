/* ============================= Constants ==================================*/

const TOAST_STATUS_LIST = {
    success: 'success',
    error: 'error',
    info: 'info',
    warning: 'warning',
};

const ACTION_LIST = {
    submitAnswers: 'submit_answers',
    addAnswer: 'add_answer',
    submitUser: 'submit_user',
    getUser: 'get_user',
}

/* ============================= Heplers ==================================*/

const getQuestionItemTitle = (targetElement) => $(targetElement).closest('.question-list__item').find('.question-list__item-title');

const runNotifier = ({
                         heading,
                         text,
                         icon = TOAST_STATUS_LIST.success,
                     }) => {
    $.toast({
        heading,
        text,
        showHideTransition: 'slide',
        icon,
        position: 'top-right',
        hideAfter: 5000,
        loader: false,
    });
};

/* ============================= Custom validators ==================================*/

$.validator.addMethod('phoneNumber', function (phoneNumber, element) {
    const normalizedPhoneNumber = phoneNumber.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>{}\[\]\\\/\s]/gi, '');

    return this.optional(element) || normalizedPhoneNumber.length === 12 &&
        normalizedPhoneNumber.match(/^\d+$/);
}, 'Вкажіть правильний номер телефону');

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
    errorPlacement: function (error) {
        $('#form_error').html(error);
    },
    submitHandler: function (form) {
        const testId = $(form).data('test-id');
        const data = {
            testId,
        };

        submitAjaxRequest({
                ...data,
                action: ACTION_LIST.submitAnswers,
            },
            () => {
                runNotifier({
                    heading: 'Вітаємо!',
                    text: 'Відповіді відправлені в обробку',
                });
                $(form).find('input[type=checkbox]').prop('checked', false);
                $(form).find('input[type=radio]').prop('checked', false);
            },
            (err) => {
                runNotifier({
                    heading: 'Помилка!',
                    text: err.responseJSON ? err.responseJSON.data : err.responseText,
                    icon: TOAST_STATUS_LIST.error,
                });
                console.log(err);
            });
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
        testId,
    };
    submitAjaxRequest({
            ...data,
            action: ACTION_LIST.addAnswer,
        },
        (res) => console.log(res),
        (err) => console.log(err),
    );
});

/* ======================= user modal ======================= */

const submitUserData = (form) => {
    const $loginForm = $(form);
    const userName = $loginForm.find('#user-name').val();
    const userPhone = $loginForm.find('#user-phone').val();
    const userEmail = $loginForm.find('#user-email').val();
    const userData = {
        userName,
        userPhone,
        userEmail,
    };

    submitAjaxRequest({
            ...userData,
            action: ACTION_LIST.submitUser,
        },
        (res) => {
            if (res.data) {
                localStorage.setItem('user', JSON.stringify(res.data));
            }
            location.reload();
        },
        (err) => {
            runNotifier({
                heading: 'Помилка!',
                text: err.responseJSON ? err.responseJSON.data : err.responseText,
                icon: TOAST_STATUS_LIST.error,
            });
            console.log(err);
        },
    );
};
const openUserDialog = () => {
    $('#login-form').modal({
        escapeClose: false,
        clickClose: false,
        showClose: false,
    });
};

const loggedUser = localStorage.getItem('user');

if (!loggedUser) {
    $('#submit-user').removeClass('dNone');
    $('.user-submit-dialog').removeClass('dNone');
    openUserDialog();
} else {
    const data = JSON.parse(loggedUser);
    submitAjaxRequest({
            userId: data.userId,
            userPhone: data.userPhone,
            action: ACTION_LIST.getUser,
        },
        (res) => {
            const $submitUserBtn = $('#submit-user');

            if (!res.data) {
                localStorage.removeItem('user');
                $submitUserBtn.removeClass('dNone');
                openUserDialog();

                return;
            }
            $('.user-info-title').removeClass('dNone');
            $('#test-user-name').text(data.userName);

            runNotifier({
                heading: `Вітання ${res.data.userName}`,
            });
        },
        (err) => {
            console.log(err);
            localStorage.removeItem('user');
            $('#submit-user').removeClass('dNone');
            $('.user-submit-dialog').removeClass('dNone');
        },
    );
}

/* ======================= input mask ======================= */

$('#user-phone').inputmask('+38 (099) 999-99-99');
$('#user-submit-form').validate({
    rules: {
        'user-name': {
            required: true,
            minlength: 3,
        },
        'user-phone': {
            required: true,
            phoneNumber: true,
        },
        'user-email': {
            required: true,
            email: true,
        },
    },
    messages: {
        'user-name': {
            required: 'Обов\'язкове поле',
            minlength: $.validator.format('Мінімальна довжина {0} символи'),
        },
        'user-phone': {
            required: 'Обов\'язкове поле',
        },
        'user-email': {
            required: 'Обов\'язкове поле',
            email: 'Невірний формат E-mail',
        },
    },
    submitHandler: function (form) {
        submitUserData(form);
        return false;
    },
});

$('.user-info-title__change').click(function (e) {
    e.preventDefault();
    openUserDialog();
    const $cancelUserBtn = $('#cancel-submit-user');
    const $submitUserBtn = $('#submit-user');

    $submitUserBtn.removeClass('dNone');
    $cancelUserBtn.removeClass('dNone');

    const $loginForm = $('#login-form');
    const $userNameInput = $loginForm.find('#user-name');
    const $userPhoneInput = $loginForm.find('#user-phone');
    const $userEmailInput = $loginForm.find('#user-email');

    $userNameInput.val('');
    $userPhoneInput.val('');
    $userEmailInput.val('');
});

$('#cancel-submit-user').click(function () {
    $.modal.close();
});
