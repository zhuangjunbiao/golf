<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __construct(Request $request)
    {
        dd(\Route::current()->getAction(), __CLASS__.'@'.__FUNCTION__);
    }

    public function getIndex()
    {
        return view('admin.auth.login');
    }
}
