$(function() {

    var iptPhone = $('input[name=phone]');
    var iptPassword = $('input[name=password]');
    var form = $('#form');

    // 表单提交
    form.submit(function() {
        $.golf.cleanFormError();

        var phone = iptPhone.val();
        var password = iptPassword.val();

        if (!$.golf.validator.phone(phone)) {
            $.golf.formError(iptPhone.data('validator'));
            iptPhone.focus();
            return false;
        }

        if (password.length < 6 || password.length > 18) {
            $.golf.formError(iptPassword.data('validator'));
            iptPassword.focus();
            return false;
        }

        return true;
    });
});