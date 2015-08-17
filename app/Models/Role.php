<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';

    protected $primaryKey = 'rid';

    protected $guarded = ['rid'];

    public $timestamps = false;
}
