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
     * @param $uid_or_user_name
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getUserInfo($uid_or_user_name)
    {
        $this->qSelect('U.*', 'RU.rid')
            ->fromAlias('U')
            ->leftJoinRoleUser();

        // 判断是uid查找还是user_name查找
        if (is_int($uid_or_user_name))
        {
            $this->qWhere('U.uid', '=', $uid_or_user_name);
        }
        elseif (is_string($uid_or_user_name))
        {
            $this->qWhere('U.user_name', '=', $uid_or_user_name);
        }
        else
        {
            return false;
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
