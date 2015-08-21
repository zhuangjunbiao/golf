@extends('admin.layout')

@section('title')
    登录
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">登录</h3>
                </div>
                <div class="panel-body">
                    <form method="post" role="form" id="form">

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
                                <input class="form-control" placeholder="手机号" name="phone" type="text" data-validator="请输入手机号" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="密码" name="password" type="password" data-validator="请输入密码">
                            </div>

                            <div class="form-group pull-right">
                                <a href="{{url('auth/forget-password')}}" class="btn btn-link">忘记密码？</a>
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
<script src="{{url_static('admin/js/auth/login.js')}}"></script>
@endsection