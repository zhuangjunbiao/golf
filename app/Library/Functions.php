<?php

if (!function_exists('ajax_return'))
{
    /**
     * ajax返回
     * @param mixed $data 数据对象
     * @param int $status 状态
     * <per>
     *      0：失败
     *      1：成功
     *      -1：未登录
     *      -2：无权限
     * </per>
     * @param null $msg 消息
     * @param null $forward 跳转地址
     * @return array
     */
    function ajax_return($data, $status=1, $msg=null, $forward=null)
    {
        $status = is_numeric($status) ? intval($status) : 0;
        $msg = is_string($msg) ? $msg : null;

        return [
            'data'      => $data,
            'status'    => $status,
            'msg'       => $msg,
            'forward'   => $forward
        ];
    }

    /**
     * ajax失败返回
     * @param int $status
     * @param null $msg
     * @param null $forward
     * @return array
     */
    function ajax_error($status=0, $msg=null, $forward=null)
    {
        return ajax_return(null, $status, $msg, $forward);
    }
}

if (!function_exists('golf_return'))
{
    /**
     * golf协议返回
     *
     * @param mixed $data 数据对象
     * @param int $errcode
     * <per>
     *      0：无错误；
     *      1001：服务器无法响应；
     *      1002：找不到资源；
     *      1003：客户端签名错误；
     *      1004：参数错误；
     *      2001：用户封禁；
     *      2002：用户输入错误；
     *      2003：未知客户端；
     * </per>
     * @param string $msg 提示消息
     * @return array
     */
    function golf_return($data, $errcode=0, $msg=null)
    {
        $msgs = array(
            1001    => 'Unknow server error.',
            1002    => 'Resource not found.',
            1003    => 'Invalid client key.',
            1004    => 'Param error.',

            2001    => 'The user has been banned.',
            2002    => 'The user input error.',
            2003    => 'Unknow client'
        );

        $msg = is_string($msg) ? $msg : null;
        $msg = is_null($msg) && isset($msgs[$errcode]) ? $msgs[$errcode] : $msg;

        return [
            'data'      => $data,
            'errcode'   => is_numeric($errcode) ? intval($errcode) : 1001,
            'msg'       => $msg
        ];
    }

    /**
     * golf协议返回错误结果
     *
     * @param int $errcode
     * @param string $msg
     * @param mixed $data
     * @return array
     */
    function golf_error($errcode, $msg=null, $data=null)
    {
        return golf_return($data, $errcode, $msg);
    }
}
