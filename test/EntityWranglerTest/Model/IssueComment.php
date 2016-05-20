<?php 

namespace EntityWranglerTest\Model;

use Ramsey\Uuid\Uuid;

class IssueComment
{

    const COLUMN_ISSUE_COMMENT_ID = 'issue_comment_id';

    const COLUMN_TEXT = 'text';

    const COLUMN_ISSUE_ID = 'issue_id';

    const COLUMN_USER_ID = 'user_id';

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

    public function toData()
    {
        $data = [];
        $data['issue_comment_id'] = $this->issueCommentId;
        $data['text'] = $this->text;
        $data['issue_id'] = $this->issueId;
        $data['user_id'] = $this->userID;

        return $data;
    }

    public static function fromData($data)
    {
        $instance = new self(
            $data['issue_comment_id'],
            $data['text'],
            $data['issue_id'],
            $data['user_id']
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
