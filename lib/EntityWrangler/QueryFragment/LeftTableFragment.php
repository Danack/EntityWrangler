<?php

namespace EntityWrangler\QueryFragment;

use EntityWrangler\EntityTableDefinition;
use EntityWrangler\Query\QueriedTable;
use EntityWrangler\QueryFragment;
use EntityWrangler\Query\Query;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;

class LeftTableFragment implements QueryFragment
{

    /**
     * @var QueriedTable
     */
    var $queriedEntity;
    
    /**
     * @var QueriedTable
     */
    var $queriedJoinTableMap = null;
    
    private $fetchColumns = true;

    function __construct(QueriedTable $entity, QueriedTable $joinTableMap)
    {
        $this->queriedEntity = $entity;
        $this->queriedJoinTableMap = $joinTableMap;
    }

    public function insertBit(Query $query) { }
    public function joinBit(Query $query) { }
    public function offsetBit(Query $query) { }
    public function onBit(Query $query) { }
    public function orderBit(Query $query) { }
    public function randBit(Query $query, &$tableMap) { }
    public function whereBit(Query $query) { }
    public function limitBit(Query $query) { }

    public function selectBit(Query $query)
    {
        $fields = [];
        foreach ($this->queriedEntity->getColumns() as $column) {
            $fields[] = sprintf(
                '%s.%s as %s_%s',
                $this->queriedEntity->getTableName(), snakify($column->name),
                $this->queriedEntity->getAlias(), snakify($column->name)
            );
        }

        return $fields;
    }

    public function tableBit(Query $query)
    {
        $fn = function (DBALQueryBuilder $queryBuilder) {
//            $queryBuilder
//    ->select('u.id', 'u.name', 'p.number')
//    ->from('users', 'u')
//    ->innerJoin('u', 'phonenumbers', 'p', 'u.id = p.user_id')
            
            $condition = sprintf(
                '%s.%s = %s.%s',
                $this->queriedJoinTableMap->getAlias(),
                $this->queriedJoinTableMap->getIdentityColumnName(),
                $this->queriedEntity->getAlias(),
                $this->queriedEntity->getIdentityColumnName()
            );
            
            return $queryBuilder->leftJoin(
                $this->queriedJoinTableMap->getAlias(),
                $this->queriedEntity->getTableName(),
                $this->queriedEntity->getAlias(),
                $condition
            );
        };
        
        return $fn;
    }
    
    
    /**
     * @return \EntityWrangler\Query\QueriedTable
     */
    public function getQueriedJoinEntity()
    {
        return $this->queriedJoinTableMap;
    }

    /**
     * @return \EntityWrangler\Query\QueriedTable
     */
    public function getQueriedEntity()
    {
        return $this->queriedEntity;
    }
    
    function setFetchColumns($boolean)
    {
        $this->fetchColumns = boolval($boolean);
    }

    /**
     * @return boolean
     */
    public function getFetchColumns() {
        return $this->fetchColumns;
    }
}
