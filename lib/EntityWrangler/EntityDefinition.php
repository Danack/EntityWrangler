<?php


namespace EntityWrangler;

interface EntityDefinition
{
    public static function getName();
    
    /** @return \EntityWrangler\Definition\Field[] */
    public static function getFields();
    
    public static function getIndexes();
    
    /** @return \EntityWrangler\Definition\Relation[] */
    public static function getRelations();
}
