<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function getIndex()
    {
        return 'admin.getIndexxxx'.time();
    }
}
