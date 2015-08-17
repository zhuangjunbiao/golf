<?php
/**
 * Created by PhpStorm.
 * User: Lenbo
 * Date: 2015/8/17
 * Time: 15:55
 */

namespace App\Library;

use Illuminate\Database\Eloquent\Builder;

trait QueryBuilder {

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * 获取对象实体
     * @return $this
     */
    public static function model()
    {
        return new self();
    }

    /**
     * 设置表别名
     * @param $alias
     * @return $this
     */
    protected function fromAlias($alias)
    {
        $alias = "{$this->table} AS {$alias}";
        $this->q()
            ->from($alias);
        return $this;
    }

    /**
     * Set the columns to be selected.
     *
     * @param  array  $columns
     * @return $this
     */
    protected function qSelect($columns = array('*'))
    {
        if (is_array($columns))
        {
            $this->q()
                ->select($columns);
        }
        else
        {
            $this->q()
                ->select(func_get_args());
        }
        return $this;
    }

    /**
     * Add a new "raw" select expression to the query.
     *
     * @param  string  $expression
     * @param  array   $bindings
     * @return $this
     */
    protected function qSelectRaw($expression, array $bindings = array())
    {
        $this->q()
            ->selectRaw($expression, $bindings);
        return $this;
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string  $column
     * @param  string  $operator
     * @param  mixed   $value
     * @param  string  $boolean
     * @return $this
     */
    protected function qWhere($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->q()
            ->where($column, $operator, $value, $boolean);
        return $this;
    }

    /**
     * @param array $columns
     * @return array
     */
    protected function qGetToArray($columns = ['*'])
    {
        $data = $this->q()->get($columns)->toArray();
        $this->flushQuery();
        return $data;
    }

    /**
     * 通过本类的查询对象执行first方法
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    protected function qFirst()
    {
        $data = $this->q()->first();
        $this->flushQuery();
        return $data;
    }

    /**
     * 刷新查询对象
     */
    protected function flushQuery()
    {
        $this->query = self::newQuery();
    }

    /**
     * 获取查询对象
     * @return Builder
     */
    protected function q()
    {
        if(!($this->query instanceof Builder))
        {
            $this->query = self::newQuery();
        }
        return $this->query;
    }
}