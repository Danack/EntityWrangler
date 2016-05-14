<?php


namespace EntityWranglerTest\EntityDescription;


use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\TableInfo;
use EntityWrangler\Definition\EntityRelation;

class IssuePriority implements EntityDefinition
{
    public static function getTableInfo()
    {
        return new TableInfo('dja', 'IssuePriority');
    }

    public static function getFields()
    {
        $fields = [];
        $fields[] = new EntityProperty('description', 'string', 'The description of the issue.');
        
        return $fields;
    }
    
    public static function getIndexes()
    {
        return [];
    }

    public static function getRelations()
    {
        $relations[] = new EntityRelation(
            'user',
            User::getTableInfo()->tableName,
            EntityRelation::ONE_TO_MANY
        );
        return [];
    }
}
