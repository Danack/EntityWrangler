<?php


namespace EntityWranglerTest\EntityDescription;

use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\TableInfo;
use EntityWrangler\Definition\EntityRelation;

class EmailAddress implements EntityDefinition
{
    public static function getTableInfo()
    {
        return new TableInfo('dja', 'EmailAddress');
    }

    public static function getFields()
    {
        $fields = [];
        $fields[] = new EntityProperty('address', 'string', 'The email address');
        
        return $fields;
    }
    
    public static function getIndexes()
    {
        return [];
    }

    public static function getRelations()
    {
        $relations = [];
        $relations[] = new EntityRelation(
            'user',
            User::getTableInfo()->tableName,
            EntityRelation::ONE_TO_ONE
        );

        return $relations;
    }
}
