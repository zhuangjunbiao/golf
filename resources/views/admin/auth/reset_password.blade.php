<form method="post">
    <p>{{$error or ''}}</p>
    <label for="iptPassword">当前密码</label><input type="password" name="password" id="iptPassword">
    <label for="iptNewPassword">新密码</label><input type="password" name="password_new" id="iptNewPassword">
    <label for="iptConfirmedPassword">确认密码</label><input type="password" name="password_confirmation" id="iptConfirmedPassword">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="submit" value="ok">
</form>