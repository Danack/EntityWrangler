<?php


namespace EntityWranglerTest\EntityDescription;

use EntityWrangler\Definition\Field;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\Name;
use EntityWrangler\Definition\Relation;

class EmailAddress implements EntityDefinition
{
    public static function getName()
    {
        return 'EmailAddress';
    }

    public static function getFields()
    {
        $fields = [];
        $fields[] = new Field('address', 'string', 'The email address');
        
        return $fields;
    }
    
    public static function getIndexes()
    {
        return [];
    }

    public static function getRelations()
    {
        $relations = [];
        $relations[] = new Relation('user', User::getName(), Relation::ONE_TO_ONE);

        return $relations;
    }
}
