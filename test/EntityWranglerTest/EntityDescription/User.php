<?php


namespace EntityWranglerTest\EntityDescription;


use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\TableInfo;

class User implements EntityDefinition
{
    public static function getTableInfo()
    {
        return new TableInfo('dja', 'User');
    }

    public static function getFields()
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
