<?php

namespace EntityWrangler;

/**
 * Interface EntityDefinition
 * 
 * Any class that implements this interface can be used to generate an entity
 */
interface EntityDefinition
{
    /** @return \EntityWrangler\Definition\EntityIdentity */
    public static function getIdentity();
    
    /** @return \EntityWrangler\Definition\TableInfo */
    public static function getTableInfo();
    
    /** @return \EntityWrangler\Definition\EntityProperty[] */
    public static function getProperties();
    
    public static function getIndexes();
    
    /** @return \EntityWrangler\Definition\EntityRelation[] */
    public static function getRelations();
}
