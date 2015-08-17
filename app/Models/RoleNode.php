<?php

namespace App\Models;

use App\Library\QueryBuilder;
use Eloquent as Model;
use Illuminate\Database\Query\JoinClause;

class RoleNode extends Model
{
    use QueryBuilder;

    protected $table = 'role_node';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * 获取角色节点
     * @param $rid
     * @return array
     */
    public function getRoleNodes($rid)
    {
        $rows = $this->qSelect('RN.*', 'N.name')
            ->fromAlias('RN')
            ->leftJoinNode()
            ->qWhere('RN.rid', '=', $rid)
            ->qGetToArray();
        return $rows;
    }

    /**
     * 关联node
     * @param int|null $status node.status值，Null表示不将status作为条件
     * @param int|null $type node.type值，Null表示不将type作为条件
     * @return $this
     */
    private function leftJoinNode($status=1, $type=3)
    {
        $N = Node::$tableName;
        $this->q()
            ->leftJoin($N, function(JoinClause $join) use ($status, $type) {
                $join->on('N.nid', '=', 'RN.nid');
                if (!is_null($status))
                {
                    $join->where('N.status', '=', $status);
                }
                if (!is_null($type))
                {
                    $join->where('N.type', '=', 3);
                }
            });
        return $this;
    }
}
