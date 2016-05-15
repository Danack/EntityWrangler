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

    public function getIssuePriorityId()
    {
        return $this->issuePriorityId;
    }

    public function getDescription()
    {
        return $this->description;
    }


}
