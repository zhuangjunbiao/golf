$(function() {

    var iptPhone = $('input[name=phone]');
    var iptSmsCode = $('input[name=sms_code]')
    var btnSmsCode = $('#btnSmsCode');
    var form = $('#form');

    // 表单提交
    form.submit(function() {
        $.golf.cleanFormError();

        var phone = iptPhone.val();
        var smsCode = iptSmsCode.val();
        if (!$.golf.validator.phone(phone)) {
            $.golf.formError(iptPhone.data('validator'));
            return false;
        }

        if (!smsCode) {
            $.golf.formError(iptSmsCode.data('validator'));
            return false;
        }

        return true;
    });

    // 获取验证码
    btnSmsCode.click(function() {
        var phone = iptPhone.val();
        var html = btnSmsCode.html();

        if (!$.golf.validator.phone(phone)) {
            $.golf.formError(iptPhone.data('validator'));
            return false;
        }

        $.ajax({
            url: '/auth/sms-code',
            data: {phone: phone},
            beforeSend: function() {
                $.golf.cleanFormError();
                btnSmsCode.attr('disabled', 'disabled');
            },
            complete: function(resp) {
                if (resp.status == 200 && resp['responseJSON']) {
                    var obj = resp['responseJSON'];
                    var time = 0;
                    if (obj['status'] == 1 || obj['status'] == -3) {
                        var timer = setInterval(function() {
                            if(time < obj['data']) {
                                btnSmsCode.html((obj['data']-time)+'秒后重新获取');
                                time++;
                            } else {
                                btnSmsCode.removeAttr('disabled');
                                btnSmsCode.html(html);
                                clearInterval(timer);
                            }
                        }, 1000);
                    } else {
                        $.golf.formError(obj['msg']);
                    }

                } else {
                    $.golf.formError('网络请求失败');
                    btnSmsCode.removeAttr('disabled');
                }
            }
        });
    });
});