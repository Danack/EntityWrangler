<?php


namespace EntityWranglerTest\EntityDescription;


use EntityWrangler\Definition\Field;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\Name;
use EntityWrangler\Definition\Relation;

class IssueComment implements EntityDefinition
{
    public static function getName()
    {
        return 'IssueComment';
    }

    public static function getFields()
    {
        $fields = [];
        $fields[] = new Field('text', 'string', 'The text of the comment');

        return $fields;
    }
    
    public static function getIndexes()
    {
        return [];
    }

    public static function getRelations()
    {
        $relations = [];
        $relations[] = new Relation('issue', Issue::getName(), Relation::ONE_TO_ONE);
        $relations[] = new Relation('user', User::getName(), Relation::ONE_TO_ONE);

        return $relations;
    }
}
