<?php

namespace EntityWrangler\QueryFragment;

use EntityWrangler\EntityWranglerException;
use EntityWrangler\QueryFragment;
use EntityWrangler\Query\Query;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWrangler\Definition\EntityProperty;

use Doctrine\DBAL\Connection;

class WhereInFragment implements QueryFragment
{
    private $whereCondition;
    private $value;
    private $sqlType;

    /**
     * @param $whereCondition
     * @param $value
     * @param $sqlType string One of s,i,
     */
    function __construct($whereCondition, $value, $sqlType)
    {
        $this->whereCondition = $whereCondition;
        $this->value = $value;
        $this->sqlType = $sqlType;
    }

    public function insertBit(Query $query) { }
    public function joinBit(Query $sqlQuery) { }
    public function offsetBit(Query $query) { }
    public function onBit(Query $sqlQuery) { }
    public function orderBit(Query $query) { }
    public function randBit(Query $sqlQuery, &$tableMap) {}
    public function limitBit(Query $query) { }
    
    public function whereBit(Query $sqlQuery)
    {
        $fn = function (DBALQueryBuilder $dbalQueryBuilder) {
            $pdoTypes = [
                //\PDO::PARAM_BOOL,
                //\PDO::PARAM_NULL,
                EntityProperty::DATA_TYPE_INT => Connection::PARAM_INT_ARRAY,
                EntityProperty::DATA_TYPE_STRING => Connection::PARAM_STR_ARRAY,
                //\PDO::PARAM_LOB,
            ];

            $pdoType = $pdoTypes[$this->sqlType];
            $namedParam = $dbalQueryBuilder->createNamedParameter($this->value, $pdoType);
            
            $this->whereCondition = ucfirst($this->whereCondition);


            $dbalQueryBuilder->where(
                $this->whereCondition.' in ( '.$namedParam.' ) '
            );
            
            return $dbalQueryBuilder;
        };
        
        return $fn;
    }

    public function selectBit(Query $query)
    {
        return null;
    }

    public function tableBit(Query $query)
    {
        return null;
    }

    function &getValue() {
        return $this->value;
    }

    function getSqlType() {
        return $this->sqlType;
    }
}

