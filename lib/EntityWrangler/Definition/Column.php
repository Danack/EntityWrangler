<?php


namespace EntityWrangler\Definition;

class Column
{
    public $name;

    public $type;

    public $description;

    public $dbName;

    function __construct($name, $type, $description, $dbName)
    {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->dbName = $dbName;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getDbName()
    {
        return $this->dbName;
    }
}
