<?php

namespace EntityWrangler\Definition;

class EntityProperty implements EntityField
{
    /** @var string The property name in a class */
    public $name;
    
    /** @var  string The property name as stored in a DB */
    public $dbName;

    public $type;

    public $description;

    const DATA_TYPE_STRING      = 'string';
    const DATA_TYPE_INT         = 'int';
    const DATA_TYPE_DATETIME    = 'datetime';
    const DATA_TYPE_HASH        = 'hash';
    const DATA_TYPE_UUID        = 'uuid';
    
    // e.g. when doing an insert, and the datetime column has default of now().
    const DATA_TYPE_NONE        = 'none';

    function __construct($name, $type, $description, $dbName = null)
    {
        if ($dbName === null) {
            $dbName = snakify($name);
        }

        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->dbName = $dbName;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPropertyName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getDBName()
    {
        return $this->dbName;
    }

    /**
     * @param $arrayOrValue
     * @return string
     */
    function getDataTypeForColumn($arrayOrValue)
    {
        if (isset($column['primary']) && $column['primary']) {
            //All primary keys are currently i.
            return self::DATA_TYPE_INT;
        }
        
        if ($this->type == EntityProperty::DATA_TYPE_DATETIME) {
            if (is_scalar($arrayOrValue) == true){
                return self::DATA_TYPE_STRING;
            }
            else if (isset($arrayOrValue[$column[0]]) == false){
                //date types when not set default to NOW(), which doesn't add a parameter
                return self::DATA_TYPE_NONE;
            }
        }
        
        return self::DATA_TYPE_STRING;
    }
}
