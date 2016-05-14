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
class EntityRelation
{
    public $fieldName;

    public $entity;

    public $relationType;

    const ONE_TO_ONE = 'one_to_one';
    const ONE_TO_MANY = 'one_to_many';

    function __construct($fieldName, $entity, $relationType)
    {
        $this->fieldName = $fieldName;
        $this->entity = $entity;
        $this->relationType = $relationType;
    }
    
    public function getColumns()
    {
        $column = new TableColumn(
            $this->fieldName."Id",
            EntityProperty::DATA_TYPE_INT,
            'Foreign key to '.$this->entity,
            snakify($this->fieldName)
        );
        
        return [
            $column
        ];
    }
}
