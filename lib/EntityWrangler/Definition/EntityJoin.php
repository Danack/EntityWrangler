<?php

namespace EntityWrangler\Definition;

/**
 * Class Join
 * 
 * Represents Entities joined through a join table
 * 
 * Table user
 *  - user_id
 *  - name
 * 
 * Table email
 *  - email_id
 *  - address
 * 
 * Table user_email_join
 *  - user_id
 *  - email_id
 * 
 */
class EntityJoin
{
    public $leftEntity;

    public $rightEntity;

    function __construct($leftEntity, $rightEntity)
    {
        $this->leftEntity = $leftEntity;
        $this->rightEntity = $rightEntity;
    }
}
