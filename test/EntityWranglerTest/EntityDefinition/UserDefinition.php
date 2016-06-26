<?php


namespace EntityWranglerTest\EntityDefinition;



use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\EntityIdentity;
use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\Definition\TableInfo;

class UserDefinition implements EntityDefinition
{
    public static function isTree()
    {
        return false;
    }
    
    public static function getIdentity()
    {
        return new EntityIdentity('userId', 'user_id');
    }
    
    public static function getTableInfo()
    {
        return new TableInfo('dja', 'User');
    }

    public static function getProperties()
    {
        $fields = [];
        $fields[] = new EntityProperty('firstName', 'string', 'The user\'s first name');
        $fields[] = new EntityProperty('lastName', 'string', 'The user\'s last name');

        return $fields;
    }

    public static function getIndexes()
    {
        return [];
    }

    public static function getRelations()
    {
        return [];
    }
}
