<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

    public static $tableName = 'role_user';
}
