<?php

namespace EntityWrangler\Fragment;

use EntityWrangler\Query\Query;
use EntityWrangler\QueryFragment;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWrangler\Query\QueriedEntity;


class OrderFragment implements QueryFragment {

    private $tableMap;
    private $column;
    private $orderValue;
    
    public function limitBit(Query $query) {}
    public function selectBit(Query $query) { }
    public function tableBit(Query $query) { }
    public function joinBit(Query $query) { }
    public function offsetBit(Query $query) { }
    public function onBit(Query $query) { }
    public function randBit(Query $query, &$tableMap) { }
    public function whereBit(Query $query) { }

    function __construct($column, QueriedEntity $tableMap = null, $orderValue= 'ASC'){
        $this->tableMap = $tableMap;
        $this->column = $column;
        $this->orderValue = $orderValue;
    }
    
    public function orderBit(Query $query)
    {
        $fn = function (DBALQueryBuilder $queryBuilder) {
            // The 'column' may actually be a group by result, and so isn't part of a table
            // or tableAlias
            if ($this->tableMap == null){
                $sort = $this->column;
            }
            else{
                $sort = $this->tableMap->getAlias().".".$this->column;
            }
            
            return $queryBuilder->orderBy(
                $sort,
                $this->orderValue
            );
        };
        
        return $fn;
    }
}
