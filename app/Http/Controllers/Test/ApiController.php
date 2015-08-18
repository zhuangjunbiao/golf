<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function getIndex(Request $request)
    {
        $data = array(
            'params'    => $request->all(),
            'version'   => ['v1'],
            'method'    => ['GET', 'POST'],
            'client'    => ['iOS', 'Android'],
        );
        return view('test.api', $data);
    }

    /**
     * 生成key
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
