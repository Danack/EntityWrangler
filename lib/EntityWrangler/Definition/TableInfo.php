<?php

namespace EntityWrangler\Definition;

class TableInfo
{
    public $schemaName;
    
    public $tableName;

    function __construct($schemaName, $tableName)
    {
        $this->schemaName = $schemaName;
        $this->tableName = $tableName;
    }
}
