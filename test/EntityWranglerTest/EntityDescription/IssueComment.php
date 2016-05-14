<?php


namespace EntityWranglerTest\EntityDescription;


use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\TableInfo;
use EntityWrangler\Definition\EntityRelation;

class IssueComment implements EntityDefinition
{
    public static function getTableInfo()
    {
        return new TableInfo('dja', 'IssueComment');
    }

    public static function getFields()
    {
        $fields = [];
        $fields[] = new EntityProperty('text', 'string', 'The text of the comment');

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
            'issue', Issue::getTableInfo()->tableName,
            EntityRelation::ONE_TO_ONE
        );
        $relations[] = new EntityRelation(
            'user',
            User::getTableInfo()->tableName,
            EntityRelation::ONE_TO_ONE
        );

        return $relations;
    }
}
