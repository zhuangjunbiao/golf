@extends('admin.layout')

@section('title')
    找回密码
@endsection

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">找回密码</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" role="form">

                        @if(isset($error))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>{{$error}}</strong>
                        </div>
                        @endif

                        <fieldset>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input class="form-control" placeholder="手机号" name="phone" type="text" autofocus>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-xs-8 col-md-6">
                                    <input class="form-control" placeholder="验证码" name="code" type="text" autofocus>
                                </div>

                                <div class="col-xs-4 col-md-6">
                                    <button class="btn btn-danger btn-block" type="button">
                                        获取验证码
                                    </button>
                                </div>
                            </div>

                            <div class="form-group pull-right">
                                <a href="{{url('auth/login')}}" class="btn btn-link">登录</a>
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
