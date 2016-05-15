<?php 

namespace EntityWranglerTest\Model;

use Ramsey\Uuid\Uuid;

class User
{

    public $userId = null;

    public $firstName = null;

    public $lastName = null;

    public function __construct($userId, $firstName, $lastName)
    {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public static function create($firstName, $lastName)
    {
        $uuid4 = UUID::uuid4();
        $instance = new self(
            $uuid4->toString(),
            $firstName,
            $lastName
        );

        return $instance;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }


}
