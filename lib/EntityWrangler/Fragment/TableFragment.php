<?php

namespace EntityWrangler\Fragment;

use EntityWrangler\Entity;
use EntityWrangler\Query\QueriedEntity;
use EntityWrangler\QueryFragment;
use EntityWrangler\Query\Query;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;

class TableFragment implements QueryFragment
{
    /**
     * @var QueriedEntity
     */
    var $queriedEntity;
    
    /**
     * @var QueriedEntity
     */
    var $queriedJoinTableMap = null;
    
    private $fetchColumns = true;

    function __construct(QueriedEntity $entity, QueriedEntity $joinTableMap = null)
    {
        $this->queriedEntity = $entity;
        $this->queriedJoinTableMap = $joinTableMap;
    }

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
            
//        *     $qb = $conn->createQueryBuilder()
//      ->select('u.name')
//      ->from('users', 'u')
//      ->join('u', 'phonenumbers', 'p', 'p.is_primary = 1');

            if ($this->queriedJoinTableMap !== nulL) {
                echo "adjsodpojs";
                $condition = sprintf(
                    '%s.%s = %s.%s',
                    $this->queriedJoinTableMap->getAlias(),
                    $this->queriedJoinTableMap->getPrimaryColumn(),
                    $this->queriedEntity->getAlias(),
                    $this->queriedEntity->getPrimaryColumn()
                );
                
                return $queryBuilder->join(
                    $this->queriedJoinTableMap->getAlias(),
                    $this->queriedEntity->getTableName(),
                    $this->queriedEntity->getAlias(),
                    $condition
                );
            }

            return $queryBuilder->from(
                $this->queriedEntity->getTableName(),
                $this->queriedEntity->getAlias()
            );
        };
        
        return $fn;
    }
    
    
    /**
     * @return \EntityWrangler\Query\QueriedEntity
     */
    public function getQueriedJoinEntity()
    {
        return $this->queriedJoinTableMap;
    }

    /**
     * @return \EntityWrangler\Query\QueriedEntity
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
