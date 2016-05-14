<?php

namespace EntityWrangler\Definition;

class EntityProperty
{
    public $name;

    public $type;

    public $description;

    const DATA_TYPE_STRING      = 'string';
    const DATA_TYPE_INT         = 'int';
    const DATA_TYPE_DATETIME    = 'datetime';
    const DATA_TYPE_HASH        = 'hash';
    
    // e.g. when doing an insert, and the datetime column has default of now().
    const DATA_TYPE_NONE        = 'none';

    function __construct($name, $type, $description)
    {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
    }
    
    public function getName()
    {
        return $this->name;
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
