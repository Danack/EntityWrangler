<?php 

namespace EntityWranglerTest\Model;

use Ramsey\Uuid\Uuid;

class Issue
{

    const COLUMN_ISSUE_ID = 'issue_id';

    const COLUMN_DESCRIPTION = 'description';

    const COLUMN_TEXT = 'text';

    const COLUMN_USER_ID = 'user_id';

    public $issueId = null;

    public $description = null;

    public $text = null;

    public $userId = null;

    public function __construct($issueId, $description, $text, $userId)
    {
        $this->issueId = $issueId;
        $this->description = $description;
        $this->text = $text;
        $this->userId = $userId;
    }

    public static function create($description, $text, $userId)
    {
        $uuid4 = UUID::uuid4();
        $instance = new self(
            $uuid4->toString(),
            $description,
            $text,
            $userId
        );

        return $instance;
    }

    public function toData()
    {
        $data = [];
        $data['issue_id'] = $this->issueId;
        $data['description'] = $this->description;
        $data['text'] = $this->text;
        $data['user_id'] = $this->userId;

        return $data;
    }

    public static function fromData($data)
    {
        $instance = new self(
            $data['issue_id'],
            $data['description'],
            $data['text'],
            $data['user_id']
        );

        return $instance;
    }

    public function getIssueId()
    {
        return $this->issueId;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getUserId()
    {
        return $this->userId;
    }


}





