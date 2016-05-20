<?php

namespace EntityWrangler\QueryFragment;

use EntityWrangler\EntityTable;
use EntityWrangler\EntityWranglerException;
use EntityWrangler\Query\QueriedTable;
use EntityWrangler\QueryFragment;
use EntityWrangler\Query\Query;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;

class TableFragment implements QueryFragment
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

    function __construct(QueriedTable $entity, QueriedTable $joinTableMap = null)
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
                $this->queriedEntity->getTableName(), $column->getDbName(),
                $this->queriedEntity->getAlias(), $column->getDbName()
            );
        }

        return $fields;
    }

    public function findJoiningColumn(QueriedTable $queriedEntity, QueriedTable $queriedJoinTableMap)
    {
        $identityColumnName = $queriedJoinTableMap->getIdentityColumnName();

        foreach ($queriedEntity->getColumns() as $column) {
            if ($column->getDBName() == $identityColumnName) {
                return $column->getDbName();
            }
        }

//        foreach ($queriedEntity->getRelations() as $relation) {
//            if ($relation->dbName == $identityColumnName) {
//                return snakify($identityColumnName);
//            }
//        }

        throw new EntityWranglerException("Failed to find joining column to join ".$queriedEntity->getTableName()." with ".$queriedJoinTableMap->getTableName());
    }


    public function tableBit(Query $query)
    {
        $fn = function (DBALQueryBuilder $queryBuilder) {
            
//        *     $qb = $conn->createQueryBuilder()
//      ->select('u.name')
//      ->from('users', 'u')
//      ->join('u', 'phonenumbers', 'p', 'p.is_primary = 1');

            if ($this->queriedJoinTableMap !== nulL) {
                $joiningColumn = $this->findJoiningColumn($this->queriedEntity, $this->queriedJoinTableMap);
                $condition = sprintf(
                    '%s.%s = %s.%s',
                    $this->queriedJoinTableMap->getAlias(),
                    $this->queriedJoinTableMap->getIdentityColumnName(),
                    $this->queriedEntity->getAlias(),
                    $joiningColumn
                );
                
                return $queryBuilder->innerJoin(
                    $this->queriedJoinTableMap->getAlias(),
                    $this->queriedEntity->getTableName(),
                    $this->queriedEntity->getAlias(),
                    $condition
                );
            }

            $tableName = $this->queriedEntity->getTableName();
            $alias = $this->queriedEntity->getAlias();

            return $queryBuilder->from(
                $tableName,
                $alias
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
