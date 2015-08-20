<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;

class IndexController extends Controller
{
    public function getIndex()
    {
        return 'admin.getIndexxxx'.time();
    }
}
