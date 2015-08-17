<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    public static $tableName = 'node';

    protected $table = 'node';

    protected $primaryKey = 'nid';

    protected $guarded = ['nid'];

    public $timestamps = false;
}
