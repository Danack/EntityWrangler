<?php 

namespace EntityWranglerTest\Model;

use Ramsey\Uuid\Uuid;

class EmailAddress
{

    public $emailAddressId = null;

    public $address = null;

    public $userId = null;

    public function __construct($emailAddressId, $address, $userId)
    {
        $this->emailAddressId = $emailAddressId;
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

    public function toData()
    {
        $data = [];
        $data['email_address_id'] = $this->emailAddressId;
        $data['address'] = $this->address;
        $data['user_id'] = $this->userId;

        return $data;
    }

    public static function fromData($data)
    {
        $instance = new self(
            $data['email_address_id'],
            $data['address'],
            $data['user_id']
        );

        return $instance;
    }

    public function getEmailAddressId()
    {
        return $this->emailAddressId;
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
