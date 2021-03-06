<?php

namespace EntityWrangler\QueryFragment;

use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWrangler\QueryFragment;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;


class GroupFragment implements QueryFragment {

    /**
     * @var QueriedTable
     */
    private $tableMap;

    /**
     * @var string
     */
    private $column;

    public function insertBit(Query $query) { }
    public function joinBit(Query $query) { }
    public function limitBit(Query $query) { }
    public function offsetBit(Query $query) { }
    public function onBit(Query $query) { }
    public function orderBit(Query $query) { }
    public function randBit(Query $query, &$tableMap) { }
    public function tableBit(Query $query) { }
    public function whereBit(Query $query) { }

    function __construct(QueriedTable $tableMap, $column)
    {
        $this->tableMap = $tableMap;
        $this->column = $column;
    }
    
    public function groupBit(Query $query)
    {
        $fn = function (DBALQueryBuilder $queryBuilder) {
            $string = $this->tableMap->getAlias()."_".$this->column;

            return $queryBuilder->groupBy($string);
        };

        return $fn;
    }

    public function selectBit(Query $query)
    {
        $fn = function (DBALQueryBuilder $queryBuilder) {
            $string = "count(1) as ".$this->tableMap->getAlias()."_".$this->column;

            return $queryBuilder->select($string);
        };

        return $fn;
    }
}
