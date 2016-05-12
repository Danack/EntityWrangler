<?php

namespace EntityWranglerTest\EntityFactory;

use EntityWranglerTest\Model\IssueComment;
use EntityWranglerTest\EntityFactory;

class IssueCommentFactory implements EntityFactory
{
    public function create(array $data)
    {
        
        $issueCommentId = extractValue($data, 'issue_comment_id');
        $issueId = extractValue($data, 'issue_id');
        $text = extractValue($data, 'text');
        $userId = extractValue($data, 'user_id');
        
        return new IssueComment($issueCommentId, $issueId, $text, $userId);
    }
}
