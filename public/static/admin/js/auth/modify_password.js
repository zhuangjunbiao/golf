(function($){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.golf = {
        // 验证规则
        validator: {

            // 手机号
            phone: function(phone) {
                var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
                return reg.test(phone);
            }
        },

        // 3秒后重定向
        forward: function(url) {
            if (url) {
                setTimeout(function() {
                    window.location = url;
                }, 3000);
            }
        },

        // 在表单输出错误消息
        formError: function(msg) {
            var html = '<div class="alert alert-danger alert-dismissible" role="alert">';
                html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                html += '<strong>'+msg+'</strong>'
                html += '</div>';
            $('#errors').html(html);
        },

        // 清除表单错误消息
        cleanFormError: function() {
            $('#errors').html('');
        }
    }
})(jQuery);
$(function() {

    var iptPhone = $('input[name=phone]');
    var iptSmsCode = $('input[name=sms_code]');
    var iptNowPassword = $('input[name=now_password]');
    var iptPassword = $('input[name=password]');
    var iptPasswordConfirmation = $('input[name=password_confirmation]');
    var btnSmsCode = $('#btnSmsCode');
    var form = $('#form');

    // 表单提交
    form.submit(function() {
        $.golf.cleanFormError();

        var phone = iptPhone.val();
        var smsCode = iptSmsCode.val();
        var nowPassword = iptNowPassword.val();
        var password = iptPassword.val();
        var passwordConfirm = iptPasswordConfirmation.val();

        if (!$.golf.validator.phone(phone)) {
            $.golf.formError(iptPhone.data('validator'));
            iptPhone.focus();
            return false;
        }

        if (!smsCode) {
            $.golf.formError(iptSmsCode.data('validator'));
            iptSmsCode.focus();
            return false;
        }

        if (!nowPassword) {
            $.golf.formError(iptNowPassword.data('validator'));
            iptNowPassword.focus();
            return false;
        }

        if (password.length < 6 || password.length > 18) {
            $.golf.formError(iptPassword.data('validator'));
            iptPassword.focus();
            return false;
        }

        if (passwordConfirm != password) {
            $.golf.formError(iptPasswordConfirmation.data('validator'));
            iptPasswordConfirmation.focus();
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
//# sourceMappingURL=modify_password.js.map