<?php


namespace EntityWranglerTest\EntityDefinition;


use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\TableInfo;
use EntityWrangler\Definition\EntityIdentity;
use EntityWrangler\Definition\EntityRelation;

class IssuePriorityDefinition implements EntityDefinition
{   
    public static function isTree()
    {
        return false;
    }
    
    public static function getIdentity()
    {
        return new EntityIdentity('issuePriorityId', 'issue_priority_id');
    }
    
    public static function getTableInfo()
    {
        return new TableInfo('dja', 'IssuePriority');
    }

    public static function getProperties()
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
            'userId', 'user_id',
            UserDefinition::getIdentity(),
            UserDefinition::getTableInfo()->tableName,
            EntityRelation::ONE_TO_MANY
        );
        return [];
    }
}
