<?php 

namespace EntityWranglerTest\Model;

use Ramsey\Uuid\Uuid;

class EmailAddress
{

    public $issueId = null;

    public $address = null;

    public $userId = null;

    public function __construct($issueId, $address, $userId)
    {
        $this->issueId = $issueId;
        $this->address = $address;
        $this->userId = $userId;
    }

    public static function create($address, $userId)
    {
        $uuid4 = UUID::uuid4();
        $instance = new self(
            $uuid4->toString(),
            $address,
            $userId
        );

        return $instance;
    }

    public function getIssueId()
    {
        return $this->issueId;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getUserId()
    {
        return $this->userId;
    }


}
