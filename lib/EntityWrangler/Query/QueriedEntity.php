<?php

namespace EntityWrangler\Query;

use EntityWrangler\SafeAccess;
use EntityWrangler\Entity;
use EntityWrangler\Query\Query;
use EntityWrangler\Definition\Field;
use EntityWrangler\UnsupportedOperationException;

class QueriedEntity
{
    use SafeAccess;

    private $alias;

    /**
     * @var Entity
     */
    private $entity;

    /**
     * @var Query
     */
    private $abstractQuery;

    function __construct(Entity $entity, $alias, Query $abstractQuery)
    {
        $this->entity = $entity;
        $this->alias = $alias;
        $this->abstractQuery = $abstractQuery;
    }

    function getEntity()
    {
        return $this->entity;
    }

    function getQuery()
    {
        return $this->abstractQuery;
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
    function getSchema(){
        return $this->getEntity()->schema;
    }

    /**
     * @return mixed
     */
    function getTableName()
    {
        return $this->getEntity()->getName();
    }

    /**
     * @return string
     */
    function getAliasedPrimaryColumn()
    {
        return $this->alias.".".$this->getEntity()->getPrimaryColumnName();
    }

    /**
     * @return bool
     */
    function getPrimaryColumnName()
    {
        return $this->getEntity()->getPrimaryColumnName();
    }

    /**
     * @return Field[]
     */
    function getColumns()
    {
        return $this->getEntity()->getFields();
    }


    function getRelations()
    {
        return $this->getEntity()->getRelations();
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
        $columnName = $this->getAliasedPrimaryColumn();
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


    /**
     * @return QueriedEntity
     */
    function rand()
    {
        $aliasedTable = $this->getQuery()->aliasTableMap($this->getEntity());
        $this->getQuery()->rand($this, $aliasedTable);
        $this->getQuery()->order($this, $this->getEntity()->getPrimaryColumnName());
        $this->getQuery()->limit(1);

        return $aliasedTable;
    }


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
        
        $dataType = $this->getEntity()->getDataTypeForColumn($column, $value);
        switch($dataType) {
            case(Field::DATA_TYPE_INT): {
                $this->getQuery()->where($sqlFunctionName.$lb.$columnName.$rb." = ?", $value, $dataType);
                break;
            }

            case(Field::DATA_TYPE_STRING): {
                $this->getQuery()->where($sqlFunctionName.$lb.$columnName .$rb." like ", $value, $dataType);
                break;
            }

            case(Field::DATA_TYPE_HASH): {
                //$passwordHasher = new PasswordHash(8, false);
                //echo "Hashing [$value]";
                //$hash = $passwordHasher->HashPassword($value);
                //echo " gives value $hash";

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
    function whereColumnIn($column, array $values) {
        $columnName = $this->getColumn($column);
        $dataType = $this->getEntity()->getDataTypeForColumn($column, null);
        $dataTypeArray = '';
        $inString = " in ( ";
        $separator = '';
        //TODO replace with count?
        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($values as $value) {
            $inString .= $separator.' ? ';
            $dataTypeArray .= $dataType;
            $separator = ', ';
        }

        $inString .= " ) ";
        $this->getQuery()->where($columnName.$inString, $values, $dataTypeArray);
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
 