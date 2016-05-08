<?php


namespace EntityWranglerTest\EntityDescription;


use EntityWrangler\Definition\Field;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\Name;
use EntityWrangler\Definition\Relation;

class IssuePriority implements EntityDefinition
{
    public static function getName()
    {
        return 'IssuePriority';
    }

    public static function getFields()
    {
        $fields = [];
        $fields[] = new Field('description', 'string', 'The description of the issue.');
        
        return $fields;
    }
    
    public static function getIndexes()
    {
        return [];
    }

    public static function getRelations()
    {
        $relations[] = new Relation('user', User::getName(), Relation::ONE_TO_MANY);
        return [];
    }
}
