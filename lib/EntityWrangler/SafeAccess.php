<?php

namespace EntityWrangler;

use EntityWrangler\EntityWranglerException;

trait SafeAccess
{
    public function __set($name, $value)
    {
        throw new EntityWranglerException("Property [$name] doesn't exist for class [".get_class($this)."] so can't set it");
    }
    public function __get($name)
    {
        throw new EntityWranglerException("Property [$name] doesn't exist for class [".get_class($this)."] so can't get it");
    }

    function __call($name, array $arguments)
    {
        throw new EntityWranglerException("Function [$name] doesn't exist for class [".get_class($this)."] so can't call it");
    }
}