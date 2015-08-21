@extends('admin.layout')

@section('title')
    {{$msg or ''}}
@endsection

@section('body')
    <h1>{{$msg or ''}}</h1>
    <p><span id="timer">{{$time or 3}}</span>秒后<a href="{{$forward or '/'}}" id="url">跳转</a>到指定页面...</p>
@endsection

@section('script')
    <script>
        $(function() {
            var sTimer = $('#timer');
            var timer = setInterval(function() {
                if (sTimer.html() <= 1) {
                    clearInterval(timer);
                    window.location = $('#url').attr('href');
                }
                else
                {
                    sTimer.html(parseInt(sTimer.html()) - 1);
                }
            }, 1000);
        });
    </script>
@endsection