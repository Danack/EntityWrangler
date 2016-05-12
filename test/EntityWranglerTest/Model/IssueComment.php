<?php

namespace EntityWranglerTest\Model;

class IssueComment
{
    public $issueCommentId;
    public $issueId;
    public $text;
    public $userId;

    function __construct($issueCommentId, $issueId, $text, $userId)
    {
        $this->issueCommentId = $issueCommentId;
        $this->issueId = $issueId;
        $this->text = $text;
        $this->userId = $userId;
    }
}
