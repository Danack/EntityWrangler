<?php


namespace EntityWrangler\Definition;

interface EntityField
{
    public function getPropertyName();
    
    public function getDbName();
}
