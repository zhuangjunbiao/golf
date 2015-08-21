@extends('admin.layout')

@section('title')
    修改密码
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">修改密码</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" role="form" id="form">

                        <div id="errors">
                            @if(isset($error))
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>{{$error}}</strong>
                                </div>
                            @endif

                            @if (count($errors) > 0)
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>{{$errors->all()[0]}}</strong>
                                </div>
                            @endif
                        </div>

                        <fieldset>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input class="form-control" placeholder="手机号" name="phone" type="text" data-validator="手机格式不正确" autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-8 col-md-6">
                                    <input class="form-control" placeholder="验证码" name="sms_code" type="text" data-validator="请输入验证码">
                                </div>

                                <div class="col-xs-4 col-md-6">
                                    <button class="btn btn-danger btn-block" id="btnSmsCode" type="button">
                                        获取验证码
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <input class="form-control" placeholder="当前密码" name="now_password" type="password" data-validator="请输入密码">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <input class="form-control" placeholder="新密码" name="password" type="password" data-validator="新密码格式错误">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <input class="form-control" placeholder="确认密码" name="password_confirmation" type="password" data-validator="两次密码不一致">
                                </div>
                            </div>

                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-lg btn-success btn-block">提交</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{url_static('admin/js/auth/modify_password.js')}}"></script>
@endsection