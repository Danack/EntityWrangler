<?php

namespace EntityWrangler\Definition;

use EntityWrangler\Definition\TableColumn;
use EntityWrangler\Definition\EntityProperty;

/**
 * Class Relation
 * 
 * Represents a join of a child table to a parent table.
 * 
 * Table user
 *  - user_id
 *  - name
 * 
 * Table email
 *  - email_id
 *  - user_id  - this is the relation  
 *  - address
 * 
 */
class EntityRelation implements EntityField
{
    public $propertyName;
    
    public $dbName;

    public $entity;

    public $relationType;

    /** @var EntityIdentity */
    public $entityIdentity;
    
    const ONE_TO_ONE = 'one_to_one';
    const ONE_TO_MANY = 'one_to_many';

    function __construct(
        $propertyName, 
        $dbName,
        EntityIdentity $entityIdentity,
        $entity, $relationType
    ) {
        $this->propertyName = $propertyName;
        $this->dbName = $dbName;
        $this->entityIdentity = $entityIdentity;
        $this->entity = $entity;
        $this->relationType = $relationType;
    }

    /**
     * @return mixed
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return mixed
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return mixed
     */
    public function getRelationType()
    {
        return $this->relationType;
    }

    /**
     * @return EntityIdentity
     */
    public function getEntityIdentity()
    {
        return $this->entityIdentity;
    }
    
    public function getColumns()
    {
        $type = EntityProperty::DATA_TYPE_INT;
        if ($this->entityIdentity->getType() == EntityIdentity::TYPE_UUID) {
            $type = EntityProperty::DATA_TYPE_STRING;
        }

        $column = new TableColumn(
            $this->propertyName,
            $type,
            'Foreign key to '.$this->entity,
            snakify($this->propertyName)
        );
        
        return [
            $column
        ];
    }
}
