<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Services\OAuth;
use Session;

class IndexController extends Controller
{
    /**
     * 页面跳转中间页
     *
     * @return \Illuminate\View\View
     */
    public function getJump()
    {
        if (Session::get('jump'))
        {
            Session::forget('jump');
            return view('admin.jump');
        }
        else
        {
            return view('errors.404');
        }
    }

    public function getIndex(OAuth $auth)
    {
        return view('admin.index');
    }
}
