<?php


namespace EntityWranglerTest\EntityDescription;


use EntityWrangler\Definition\Field;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\Name;

class User implements EntityDefinition
{
    public static function getName()
    {
        return 'User';
    }

    public static function getFields()
    {
        $fields = [];
        $fields[] = new Field('firstName', 'string', 'The user\'s first name');
        $fields[] = new Field('lastName', 'string', 'The user\'s last name');
        
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
