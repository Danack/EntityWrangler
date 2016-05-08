<?php


namespace EntityWrangler;

interface EntityDefinition
{
    public static function getName();
    
    public static function getFields();
    
    public static function getIndexes();
    
    public static function getRelations();
}
