<?php


namespace EntityWrangler\QueryFragment;

use EntityWrangler\Query\Query;
use EntityWrangler\QueryFragment;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWrangler\Query\QueriedTable;

class InsertFragment implements QueryFragment
{
    /** @var QueriedTable */
    private $tableMap;
    
    /** @var array */
    private $data;
    
    function __construct(QueriedTable $tableMap, array $data)
    {
        $this->tableMap = $tableMap;
        $this->data = $data;
    }
    
    public function insertBit(Query $query)
    {
        $values = [];
        $params = [];
        
        foreach ($this->tableMap->getColumns() as $column) {
            $values[$column->getName()] = '?';
            $params[$column->getName()] = $this->data[$column->getName()];
        }
        
        $fn = function (DBALQueryBuilder $queryBuilder) use ($values, $params) { 
            return $queryBuilder
              ->insert($this->tableMap->getAlias())
              ->values($values)
              ->setParameters($params);
        };
        
        return $fn;
    }

    public function joinBit(Query $query) {}

    public function limitBit(Query $query) {}

    public function offsetBit(Query $query) {}

    public function onBit(Query $query) {}

    public function orderBit(Query $query) {}

    public function randBit(Query $query, &$tableMap) {}

    public function selectBit(Query $query) {}

    public function tableBit(Query $query) {}

    public function whereBit(Query $query) {}


}
