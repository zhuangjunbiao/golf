<?php namespace App\Models;

use App\Library\QueryBuilder;
use Eloquent;

class Node extends Eloquent
{
    use QueryBuilder;

    public static $tableName = 'node';

    protected $table = 'node';

    protected $primaryKey = 'nid';

    protected $guarded = ['nid'];

    public $timestamps = false;

    /**
     * 获取所有节点
     *
     * @param $rid
     * @param int $status
     * @return array
     */
    public function getUses($rid, $status=1)
    {
        // 获取节点id
        $rows = RoleNode::where('rid', '=', $rid)->get()->toArray();
        if (empty($rows))
        {
            return [];
        }

        $this->fromAlias('N')
            ->whereEqStatus($status);

        if ($rows[0]['nid'] != '*')
        {
            $nids = array_column($rows, 'nid');
            $this->whereInNId($nids);
        }

        $rows = $this->qGetToArray();

        return $rows;
    }

    /**
     * 条件：N.status=$status
     *
     * @param $status
     * @return $this
     */
    private function whereEqStatus($status)
    {
        if (!is_null($status))
        {
            $this->q()
                ->where('N.status', '=', $status);
        }

        return $this;
    }

    /**
     * 条件：N.nid IN ($nids)
     *
     * @param array $nids
     * @return $this
     */
    private function whereInNId(array $nids)
    {
        if (!(empty($nids)))
        {
            $this->q()
                ->whereIn('N.nid', $nids);
        }

        return $this;
    }
}
