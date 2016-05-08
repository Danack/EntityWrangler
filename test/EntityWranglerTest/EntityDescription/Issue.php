<?php


namespace EntityWranglerTest\EntityDescription;


use EntityWrangler\Definition\Field;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\Name;
use EntityWrangler\Definition\Relation;

class Issue implements EntityDefinition
{
    public static function getName()
    {
        return 'Issue';
    }

    public static function getFields()
    {
        $fields = [];
        $fields[] = new Field('description', 'string', 'The description of the issue.');
        $fields[] = new Field('text', 'string', 'the text of the issue');

        return $fields;
    }
    
    public static function getIndexes()
    {
        return [];
    }

    public static function getRelations()
    {
        $relations = [];
        $relations[] = new Relation('user', User::getName(), Relation::ONE_TO_MANY);
        //$relations[] = new Relation('issuePriority', IssuePriority::getName(), Relation::ONE_TO_ONE);
        return $relations;
    }
}
