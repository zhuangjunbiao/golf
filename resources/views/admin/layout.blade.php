<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - {{Lang::get('global.web_name')}}</title>

    <link href="{{url_plugin('bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url_plugin('metisMenu/dist/metisMenu.min.css')}}" rel="stylesheet">
    <link href="{{url_static('admin/css/sb-admin-2.css')}}" rel="stylesheet">
    <link href="{{url_plugin('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    @yield('head')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

@yield('body')

@section('script')
<script src="{{url_plugin('jquery/dist/jquery.min.js')}}"></script>
<script src="{{url_plugin('bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{url_plugin('metisMenu/dist/metisMenu.min.js')}}"></script>
<script src="{{url_static('admin/js/sb-admin-2.js')}}"></script>
@show
</body>
</html>