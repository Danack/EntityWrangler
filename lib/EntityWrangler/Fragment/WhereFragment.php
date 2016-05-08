<?php

namespace EntityWrangler\Fragment;

use EntityWrangler\QueryFragment;
use EntityWrangler\Query\Query;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWrangler\Definition\Field;

class WhereFragment implements QueryFragment
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
        if($sqlType === NULL) {
            if($value !== NULL) {
                throw new \BadFunctionCallException("Value is set for where fragment. You must also set type.");
            }
        }
        
        $this->whereCondition = $whereCondition;
        $this->value = $value;
        $this->sqlType = $sqlType;
    }
    
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
                Field::DATA_TYPE_INT => \PDO::PARAM_INT,
                Field::DATA_TYPE_STRING => \PDO::PARAM_STR,
                //\PDO::PARAM_LOB,
            ];
            
            $pdoType = $pdoTypes[$this->sqlType];
            $namedParam = $dbalQueryBuilder->createNamedParameter($this->value, $pdoType);
            $dbalQueryBuilder->where(
                $this->whereCondition.' '.$namedParam
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

