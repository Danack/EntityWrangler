<?php 

namespace EntityWranglerTest\Model;

use Ramsey\Uuid\Uuid;

class IssuePriority
{

    public $issuePriorityId = null;

    public $description = null;

    public function __construct($issuePriorityId, $description)
    {
        $this->issuePriorityId = $issuePriorityId;
        $this->description = $description;
    }

    public static function create($description)
    {
        $uuid4 = UUID::uuid4();
        $instance = new self(
            $uuid4->toString(),
            $description
        );

        return $instance;
    }

    public function toData()
    {
        $data = [];
        $data['issue_priority_id'] = $this->issuePriorityId;
        $data['description'] = $this->description;

        return $data;
    }

    public static function fromData($data)
    {
        $instance = new self(
            $data['issue_priority_id'],
            $data['description']
        );

        return $instance;
    }

    public function getIssuePriorityId()
    {
        return $this->issuePriorityId;
    }

    public function getDescription()
    {
        return $this->description;
    }


}
