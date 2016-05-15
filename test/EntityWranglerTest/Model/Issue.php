<?php 

namespace EntityWranglerTest\Model;

use Ramsey\Uuid\Uuid;

class Issue
{

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
