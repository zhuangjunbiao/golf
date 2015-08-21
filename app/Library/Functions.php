<?php

if (!function_exists('fix_uses'))
{
    /**
     * 转换SB Admin导航
     *
     * @param $uses
     * @param int $level
     * @return string
     */
    function fix_sb_admin_nav($uses, $level=1)
    {
        $tmp = '';
        if ($level == 1)
        {
            $nav_level = 'nav-second-level';
        }
        elseif ($level == 2)
        {
            $nav_level = 'nav-third-level';
        }

        foreach ($uses as $k => $v)
        {
            $url = url($v['access']);
            $name = $v['nname'];

            if (empty($v['sub']))
            {
                $tmp .= '<li><a href="'.$url.'">'.$name.'</a></li>';
            }
            else
            {
                $tmp .= '<li>
                    <a href="'.$url.'">'.$name.'<span class="fa arrow"></span></a>
                    <ul class="nav '.$nav_level.'">
                        '.fix_sb_admin_nav($v['sub'], $level + 1).'
                    </ul>
                </li>';
            }
        }

        return $tmp;
    }
}

if (!function_exists('fix_avatar'))
{
    /**
     * 转换头像
     *
     * @param array $data
     * @return array
     */
    function fix_avatar(array &$data)
    {
        $avatar_host = config('global.avatar_host');
        foreach ($data as $k => $row)
        {
            if (!isset($row['avatar']) || empty($row['avatar']))
            {
                $row['avatar'] = "{$avatar_host}/global/avatar.gif";
            }
            else
            {
                $row['avatar'] = "{$avatar_host}/{$row['avatar']}";
                $row['avatar_little'] = "{$avatar_host}/64_64/{$row['avatar']}";
            }

            $data[$k] = $row;
        }

        return $data;
    }
}

if (!function_exists('fix_apps'))
{
    /**
     * app键值转换
     *
     * @param $value
     * @param bool|true $inversion
     * @return mixed
     */
    function fix_apps($value, $inversion=true)
    {
        $apps = config('global.apps');
        if ($inversion)
        {
            return array_search($value, $apps);
        }
        else
        {
            return $apps[$value];
        }
    }
}

if (!function_exists('console'))
{
    /**
     * 打印日志
     *
     * @param $data
     */
    function console($data)
    {
        \App\Library\Console::log($data);
    }
}

if (!function_exists('rand_str'))
{
    /**
     * 产生随机字符串
     *
     * @param int $length
     * @param int $type
     * @return string
     */
    function rand_str($length=5, $type=0)
    {
        // 去除比较相似的ilo01ILO
        $number = '23456789';
        $letter = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
        if ($type == 0)
        {
            // 纯数字补充01
            $chars = $number.'01';
        }
        elseif ($type == 1)
        {
            // 纯字母
            $chars = $letter.'iloILO';
        }
        else
        {
            // 混合
            $chars = $number.$letter;
        }

        mt_srand((double)microtime() * 1000000 * getmypid());
        $return = '';
        while (strlen($return) < $length)
        {
            $return .= substr($chars, (mt_rand() % strlen($chars)), 1);
        }

        return $return;
    }
}

if (!function_exists('url_jump'))
{
    /**
     * 跳转中间页
     * @param $msg
     * @param string $forward
     * @param int $time
     * @return $this
     */
    function url_jump($msg, $forward='/', $time=3)
    {
        Session::put('jump', true);
        return redirect()->to('jump')->withInput([
            'msg'   => $msg,
            'forward'   => $forward,
            'time'  => $time,
        ]);
    }
}

if (!function_exists('url_plugin'))
{
    /**
     * 插件url生成
     *
     * @param $source
     * @return string
     */
    function url_plugin($source)
    {
        return url('/static/plugins'). '/' . $source;
    }
}

if (!function_exists('url_static'))
{
    /**
     * 静态文件url生成
     *
     * @param $source
     * @return string
     */
    function url_static($source)
    {
        return url('/static'). '/' . $source;
    }
}

if (!function_exists('ajax_return'))
{
    /**
     * ajax返回
     *
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
     *      1005：时间校验失败；
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
            1003    => 'Invalid key.',
            1004    => 'Param error.',
            1005    => 'The timestamp error.',

            2001    => 'The user has been banned.',
            2002    => 'The user input error.',
            2003    => 'Unknow client'
        );

        $msg = is_string($msg) ? $msg : null;
        $msg = is_null($msg) && isset($msgs[$errcode]) ? $msgs[$errcode] : $msg;

        return [
            'data'      => $data,
            'errcode'   => is_numeric($errcode) ? intval($errcode) : 1001,
            'msg'       => $msg,
            'ts'        => time()
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
