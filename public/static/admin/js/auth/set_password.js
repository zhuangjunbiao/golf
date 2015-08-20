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
//# sourceMappingURL=set_password.js.map