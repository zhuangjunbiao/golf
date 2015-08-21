<?php namespace App\Models;

use App\Library\QueryBuilder;
use Eloquent;

class Role extends Eloquent
{
    use QueryBuilder;

    protected $table = 'role';

    protected $primaryKey = 'rid';

    protected $guarded = ['rid'];

    public $timestamps = false;
}
