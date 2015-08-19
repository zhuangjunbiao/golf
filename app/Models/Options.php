<?php

namespace App\Models;

use App\Library\QueryBuilder;
use Eloquent;
use Cache;

class Options extends Eloquent
{
    use QueryBuilder;

    public static $tableName = 'options';

    protected $table = 'options';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * 获取用户名禁用词
     *
     * @return array|mixed
     */
    public static function getUserNameDeny()
    {
        $key = 'user_name_deny';
        $cache = Cache::get($key);

        if (config('app.debug'))
        {
            $cache = null;
        }

        if (empty($cache))
        {
            $deny = self::where('option_name', '=', 'user_name_deny')->first();
            if (empty($deny))
            {
                return [];
            }

            $deny = $deny->getAttribute('option_value');
            $cache = explode('，', $deny);
            Cache::put($key, $cache, 24 * 60);
        }

        return $cache;
    }
}
