<?php

namespace App\Models;

use App\Library\QueryBuilder;
use Eloquent as Model;

class Users extends Model
{
    use QueryBuilder;

    protected $table = 'users';

    protected $primaryKey = 'uid';

    protected $guarded = ['uid'];

    public $timestamps = false;

    /**
     * 获取用户信息
     * @param $value
     * @param string $field
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getUserInfo($value, $field='phone')
    {
        $this->qSelect('U.*', 'RU.rid')
            ->fromAlias('U')
            ->leftJoinRoleUser();

        if ($field == 'phone')
        {
            $this->qWhere('U.phone', '=', $value);
        }
        elseif ($field == 'uid')
        {
            $this->qWhere('U.uid', '=', $value);
        }
        else
        {
            return null;
        }

        $row = $this->qFirst();
        return $row;
    }

    /**
     * 关联role_user
     * @return $this
     */
    private function leftJoinRoleUser()
    {
        $RU = RoleUser::$tableName.' AS RU';
        $this->q()
            ->leftJoin($RU, 'RU.uid', '=', 'U.uid');
        return $this;
    }
}
