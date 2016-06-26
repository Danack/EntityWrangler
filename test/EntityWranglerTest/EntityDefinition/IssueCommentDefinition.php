<?php


namespace EntityWranglerTest\EntityDefinition;


use EntityWrangler\Definition\EntityProperty;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\TableInfo;
use EntityWrangler\Definition\EntityIdentity;
use EntityWrangler\Definition\EntityRelation;

class IssueCommentDefinition implements EntityDefinition
{
    public static function isTree()
    {
        return true;
    }

    public static function getIdentity()
    {
        return new EntityIdentity('issueCommentId', 'issue_comment_id');
    }
    
    public static function getTableInfo()
    {
        return new TableInfo('dja', 'IssueComment');
    }

    public static function getProperties()
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
            'issueId', 'issue_id',
            IssueDefinition::getIdentity(),
            IssueDefinition::getTableInfo()->tableName,
            EntityRelation::ONE_TO_ONE
        );
        $relations[] = new EntityRelation(
            'userID', 'user_id',
            UserDefinition::getIdentity(),
            UserDefinition::getTableInfo()->tableName,
            EntityRelation::ONE_TO_ONE
        );

        return $relations;
    }
}
