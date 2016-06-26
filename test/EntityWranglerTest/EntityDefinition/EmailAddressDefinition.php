<?php


namespace EntityWranglerTest\EntityDefinition;

use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\TableInfo;
use EntityWrangler\Definition\EntityIdentity;
use EntityWrangler\Definition\EntityRelation;


class EmailAddressDefinition implements EntityDefinition
{
    public static function isTree()
    {
        return false;
    }
    
    public static function getIdentity()
    {
        return new EntityIdentity('emailAddressId', 'email_address_id');
    }
    
    public static function getTableInfo()
    {
        return new TableInfo('dja', 'EmailAddress');
    }

    public static function getProperties()
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
            'userId', 'user_id',
            UserDefinition::getIdentity(),
            UserDefinition::getTableInfo()->tableName,
            EntityRelation::ONE_TO_ONE
        );

        return $relations;
    }
}
