<?php


namespace EntityWrangler\Definition;

class EntityIdentity implements EntityField
{
    const TYPE_UUID = 'uuid';
    const TYPE_PRIMARY = 'primary';
    
    /** @var string */
    private $propertyName;
    
    /** @var string */
    private $dbName;
    
    /** @var string */
    private $type;

    function __construct($propertyName, $dbName, $type = self::TYPE_UUID)
    {
        $this->propertyName = $propertyName;
        $this->dbName = $dbName;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
