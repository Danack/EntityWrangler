<?php

namespace EntityWranglerTest\Model;

class User
{
    public $userId;
    
    public $firstName;
    
    public $lastName;

    function __construct($userId, $firstName, $lastName)
    {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}
