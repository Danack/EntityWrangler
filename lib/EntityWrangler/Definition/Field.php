<?php

namespace EntityWrangler\Definition;

class Field
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
        
        if ($this->type == Field::DATA_TYPE_DATETIME) {
            if (is_scalar($arrayOrValue) == true){
                return self::DATA_TYPE_STRING;
            }
            else if (isset($arrayOrValue[$column[0]]) == false){
                //date types when not set default to NOW(), which doesn't add a parameter
                return self::DATA_TYPE_NONE;
            }
        }
        
        return self::DATA_TYPE_STRING;
        
//        //Found the column
//        if(isset($column['type']) == true && $column['type'] == 'i'){
//            return self::DATA_TYPE_INT;
//        }
//        if(isset($column['type']) == true && $column['type'] == 'hash'){
//            return 'hash';
//        }
//        if(isset($column['type']) == true && $column['type'] == 'text'){
//            return 'text';
//        }
//        else if(isset($column['type']) == true &&
//            $column['type'] == 'd'){
//        
//            if (is_scalar($arrayOrValue) == true){
//                return 's';
//            }
//            else if (isset($arrayOrValue[$column[0]]) == false){
//            //date types when not set default to NOW(), which doesn't add a parameter
//                return false;
//            }
//        }
//        else{
//            //Strings, hashes
//            return 's';
//        }
            //}
//        }

//        $columns = '['.var_export($this->columns, true).']';
//
//        throw new \Exception("Failed to find columnName [$columnNameToFind] in tableMap: ".$this->schema.".".$this->tableName." Columns are: ".$columns);
    }

    
    
}
