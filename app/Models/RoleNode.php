<?php namespace App\Models;

use App\Library\QueryBuilder;
use Eloquent;
use Illuminate\Database\Query\JoinClause;

class RoleNode extends Eloquent
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
        $rows = $this->qSelect('RN.*', 'N.nname', 'N.uses', 'N.pid')
            ->fromAlias('RN')
            ->leftJoinNode()
            ->qWhere('RN.rid', '=', $rid)
            ->qGetToArray();

        return $rows;
    }

    /**
     * 关联node
     * @param int|null $status node.status值，Null表示不将status作为条件
     * @return $this
     */
    private function leftJoinNode($status=1)
    {
        $N = Node::$tableName.' AS N';
        $this->q()
            ->leftJoin($N, function(JoinClause $join) use ($status) {
                $join->on('N.nid', '=', 'RN.nid');
                if (!is_null($status))
                {
                    $join->where('N.status', '=', $status);
                }
            });

        return $this;
    }
}
