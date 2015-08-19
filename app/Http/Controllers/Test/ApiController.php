<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    public function getIndex()
    {
        return fix_apps('comment');
    }

    /**
     * API调试工具
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getApi(Request $request)
    {
        $data = array(
            'params'    => $request->except(['__url', '__method']),
            'version'   => ['v1'],
            'method'    => ['GET', 'POST'],
            'ts'        => REQUEST_TIME,
            '__url'     => $request->input('__url'),
            '__method'  => strtoupper($request->input('__method')),
            'client'    => ['ios', 'android'],
        );
        return view('test.api', $data);
    }

    /**
     * 生成key
     *
     * @param Request $request
     * @return array
     */
    public function postKey(Request $request)
    {
        $separate = env('API_SEPARATE');
        $private_key = config('app.key');

        $str = '';
        foreach ($request->all() as $k => $v)
        {
            $str .= $k . $separate . $v;
        }
        $str .= $private_key;
        $str = md5($str);

        return ajax_return($str);
    }
}
