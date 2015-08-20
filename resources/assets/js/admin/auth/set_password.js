$(function() {
    var form = $('#form');
    var iptPassword = $('input[name=password]');
    var iptPasswordConfirmation = $('input[name=password_confirmation]');

    // 表单提交
    form.submit(function() {
        $.golf.cleanFormError();
        var password = iptPassword.val();
        var passwordConfirm = iptPasswordConfirmation.val();

        if (!password) {
            $.golf.formError(iptPassword.data('validator'));
        }

        if (password != passwordConfirm) {
            $.golf.formError(iptPasswordConfirmation.data('validator'));
        }

        return true;
    });
});