<?php

namespace EntityWrangler\Definition;

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
class Relation
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
}
