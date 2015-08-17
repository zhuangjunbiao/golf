<form method="post">

    <label for="iptUserName">用户名</label><input type="text" name="user_name" id="iptUserName">
    <label for="iptPassword">密码</label><input type="password" name="password" id="iptPassword">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="submit" value="ok">
</form>