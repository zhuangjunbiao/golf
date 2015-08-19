<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API调试工具</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>API调试工具</h1>

    <form class="form-horizontal" id="form">

        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2" for="inputAPI">API</label>
            <div class="col-md-5 col-sm-8">
                <div class="input-group">
                    <span class="input-group-addon" id="protocol">http://{{config('global.api_domain')}}/</span>
                    <select class="form-control" id="version">
                        @foreach($version as $v)
                            <option value="{{$v}}">{{$v}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 col-sm-2 control-label" for="method">METHOD</label>
            <div class="col-md-5 col-sm-8">
                <select class="form-control" id="method">
                    @foreach($method as $m)
                        <option value="{{$m}}" @if($m == $__method) selected @endif>{{$m}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 col-sm-2 control-label" for="inputCLIENT">客户端</label>
            <div class="col-md-5 col-sm-8">
                <select class="form-control" id="inputCLIENT" name="client">
                    @foreach($client as $c)
                        <option value="{{$c}}">{{$c}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 col-sm-2 control-label" for="inputTS">时间戳</label>
            <div class="col-md-5 col-sm-8">
                <input type="text" class="form-control disabled" id="inputTS" name="ts" value="{{$ts}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 col-sm-2 control-label" for="inputURL">详细地址</label>
            <div class="col-md-5 col-sm-8">
                <input type="text" class="form-control" id="inputURL" placeholder="config/init" value="{{$__url}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 col-sm-2 control-label">完整地址</label>
            <div class="col-md-5 col-sm-8">
                <p class="form-control-static" id="detailURL"></p>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 col-sm-2 control-label">参数</label>
            <div class="col-md-3 col-sm-6">
                <div class="input-group">
                    <div class="input-group-addon param-name">参数名：</div>
                    <input type="text" class="form-control" id="paramName">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <button type="button" class="btn btn-info" id="btnAddParam">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>
        </div>

        <div id="groupParams">
            @foreach($params as $name => $value)
                <div class="form-group">
                    <label class="col-md-2 col-sm-2 control-label">&nbsp;</label>
                    <div class="col-md-5 col-sm-6">
                        <div class="input-group">
                            <div class="input-group-addon param-name">{{$name}}</div>
                            <input type="text" class="form-control ipt-param" name="{{$name}}" value="{{$value}}">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <button class="btn btn-danger btn-param-remove" type="button">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="hide" id="tempParams">
            <div class="form-group">
                <label class="col-md-2 col-sm-2 control-label">&nbsp;</label>
                <div class="col-md-5 col-sm-6">
                    <div class="input-group">
                        <div class="input-group-addon param-name"></div>
                        <input type="text" class="form-control ipt-param">
                    </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <button class="btn btn-danger btn-param-remove" type="button">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-default">提交</button>
            </div>
        </div>
    </form>

    <h2>请求结果</h2>
    <pre id="respJson"></pre>

    <h2>错误信息</h2>
    <div id="respError"></div>
</div>


<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    $(function() {
        setInterval(function() {
            var ipt = $('#inputTS');
            ipt.val(parseInt(ipt.val()) + 5);
        }, 5000);

        function setDetailURL() {
            var url = $('#protocol').html()+$('#version').val()+'/'+$('#inputURL').val();
            $('#detailURL').html(url);
            return url;
        }
        setDetailURL();

        $('#inputURL').bind('input propertychange', function() {
            setDetailURL();
        });
        $('.btn-param-remove').click(function() {
            $(this).parents()[1].remove();
        });

        $('#btnAddParam').click(function() {
            var iptParamName = $('#paramName');
            var name = iptParamName.val();
            var tmp = $('#tempParams');

            if (name == '') {
                iptParamName.focus();
                return false;
            }

            tmp.find('.param-name').html(name);
            tmp.find('.ipt-param').attr('name', name);
            $('#groupParams').append(tmp.html());

            iptParamName.val('');
            tmp.find('.param-name').html('');
            tmp.find('.ipt-param').removeAttr('name');

            $('.btn-param-remove').click(function() {
                $(this).parents()[1].remove();
            });
        });

        $('#form').submit(function() {
            var form = $('#form');
            $('#respError').html('');
            $.ajax({
                url: '/test/key',
                data: form.serialize(),
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    $('#respJson').html('create key...');
                },
                success: function(resp) {
                    $.ajax({
                        url: setDetailURL(),
                        data: form.serialize()+'&key='+resp.data,
                        type: $('#method').val(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#respJson').html('requesting...');
                        },
                        success: function(data) {
                            $('#respJson').html(JSON.stringify(data, null, 2));
                        },
                        error: function(resp) {
                            alert('请求失败');
                            $('#respJson').html("HTTP "+resp.status);
                            $('#respError').html(resp.responseText);
                        }
                    });
                },
                error: function() {
                    $('#respJson').html('create key fail...');
                }
            });


            return false;
        });
    });
</script>
</body>
</html>