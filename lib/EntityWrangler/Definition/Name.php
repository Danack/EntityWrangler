<?php

namespace EntityWrangler\Definition;

class Name
{
    public $schemaName;
    
    public $tableName;

    function __construct($schemaName, $tableName)
    {
        $this->schemaName = $schemaName;
        $this->tableName = $tableName;
    }
}
