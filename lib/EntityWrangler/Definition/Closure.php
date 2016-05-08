<?php

namespace EntityWrangler\Definition;

/**
 * Class Closure
 * 
 * Represents a closure join to the same entity
 * 
 * 
 */
class Closure
{
    public $selfEntity;

    function __construct($selfEntity)
    {
        $this->selfEntity = $selfEntity;
    }
}
