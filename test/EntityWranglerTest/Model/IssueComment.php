<?php 

namespace EntityWranglerTest\Model;

use Ramsey\Uuid\Uuid;

class IssueComment
{

    public $issueCommentId = null;

    public $text = null;

    public $issueId = null;

    public $userID = null;

    public function __construct($issueCommentId, $text, $issueId, $userID)
    {
        $this->issueCommentId = $issueCommentId;
        $this->text = $text;
        $this->issueId = $issueId;
        $this->userID = $userID;
    }

    public static function create($text, $issueId, $userID)
    {
        $uuid4 = UUID::uuid4();
        $instance = new self(
            $uuid4->toString(),
            $text,
            $issueId,
            $userID
        );

        return $instance;
    }

    public function getIssueCommentId()
    {
        return $this->issueCommentId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getIssueId()
    {
        return $this->issueId;
    }

    public function getUserID()
    {
        return $this->userID;
    }


}
