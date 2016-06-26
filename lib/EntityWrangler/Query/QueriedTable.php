<?php

namespace EntityWrangler\Query;

use EntityWrangler\SafeAccess;
use EntityWrangler\EntityTableDefinition;
use EntityWrangler\Query\Query;
use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\UnsupportedOperationException;

class QueriedTable
{
    use SafeAccess;

    private $alias;

    /**
     * @var EntityTableDefinition
     */
    private $entityTable;

    /**
     * @var Query
     */
    private $query;

    function __construct(EntityTableDefinition $entity, $alias, Query $abstractQuery)
    {
        $this->entityTable = $entity;
        $this->alias = $alias;
        $this->query = $abstractQuery;
    }

    function getEntityTable()
    {
        return $this->entityTable;
    }
    
    public function limit($limit)
    {
        $this->query->limit($limit);

        return $this;
    }
    
    public function order($column, $orderValue = 'ASC')
    {
        $this->query->order($this, $column, $orderValue);
    }

    function getQuery()
    {
        return $this->query;
    }

    /**
     * @return mixed
     */
    function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    function getSchema()
    {
        return $this->getEntityTable()->schema;
    }

    /**
     * @return mixed
     */
    function getTableName()
    {
        return $this->getEntityTable()->getName();
    }

    /**
     * @return string
     */
    function getAliasedIdentityColumn()
    {
        return $this->alias.".".$this->getEntityTable()->getIdentityColumnName();
    }

    /**
     * @return bool
     */
    function getIdentityColumnName()
    {
        return $this->getEntityTable()->getIdentityColumnName();
    }

    /**
     * @return \EntityWrangler\Definition\EntityField[]
     */
    function getColumns()
    {
        $properties = $this->getEntityTable()->getProperties();
        $relations = $this->getEntityTable()->getRelations();

        return array_merge($properties, $relations);
    }


    /**
     * @return \EntityWrangler\Definition\EntityRelation[]
     */
    function getRelations()
    {
        return $this->getEntityTable()->getRelations();
    }

    /**
     * @param $column
     * @return string
     */
    function getColumn($column)
    {
        return $this->alias.".".$column;
    }

    /**
     * @param $value
     * @return $this
     */
    function wherePrimary($value)
    {
        $columnName = $this->getAliasedIdentityColumn();
        $this->getQuery()->where("$columnName = ?", $value, 'i');
        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    function whereColumn($column, $value)
    {
        return $this->whereColumnInternal(null, $column, $value);
    }


//    /**
//     * @return QueriedTable
//     */
//    function rand()
//    {
//        $aliasedTable = $this->getQuery()->aliasTableMap($this->getEntityTable());
//        $this->getQuery()->rand($this, $aliasedTable);
//        $this->getQuery()->order($this, $this->getEntityTable()->getIdentityColumnName());
//        $this->getQuery()->limit(1);
//
//        return $aliasedTable;
//    }


    /**
     * @param $sqlFunctionName
     * @param $column
     * @param $value
     * @return $this
     * @throws UnsupportedOperationException
     */
    protected function whereColumnInternal($sqlFunctionName, $column, $value)
    {
        $columnName = snakify($this->getColumn($column));

        $lb = '';
        $rb = '';
        if ($sqlFunctionName) {
            $lb = '(';
            $rb = ')';
        }
        
        $dataType = $this->getEntityTable()->getDataTypeForColumn($column, $value);
        switch($dataType) {
            case(EntityProperty::DATA_TYPE_INT): {
                $this->getQuery()->where($sqlFunctionName.$lb.$columnName.$rb." = ?", $value, $dataType);
                break;
            }

            case(EntityProperty::DATA_TYPE_STRING): {
                $this->getQuery()->where($sqlFunctionName.$lb.$columnName .$rb." like ", $value, $dataType);
                break;
            }

            case(EntityProperty::DATA_TYPE_HASH): {
                $options = array('cost' => 11);
                $hash = password_hash($value, PASSWORD_BCRYPT, $options);
                $this->getQuery()->where($sqlFunctionName.$lb.$columnName .$rb." = ? ", $hash, $dataType);
                break;
            }

            default:{
                throw new UnsupportedOperationException(
                    "Can't handle data type [$dataType] yet for column $column."
                );
                break;
            }
        }

        return $this;
    }


    /**
     * @param $column
     * @param array $values
     * @return $this
     */
    function whereColumnIn($column, array $values)
    {
        $dataType = $this->getEntityTable()->getDataTypeForColumn($column, null);
        $columnName = $this->getColumn(snakify($column));
        $this->query->whereIn($columnName, $values, $dataType);
        return $this;
    }


    /**
     * @param $functionName
     * @param $column
     * @param $value
     * @return $this
     */
    function whereColumnFunction($functionName, $column,  $value)
    {
        return $this->whereColumnInternal($functionName, $column, $value);
    }
}
 