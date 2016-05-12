<?php

namespace EntityWranglerTest\EntityFactory;

use EntityWranglerTest\Model\User;
use EntityWranglerTest\EntityFactory;

class UserFactory implements EntityFactory
{
    public function create(array $data)
    {
        $userId = extractValue($data, 'user_id');
        $firstName = extractValue($data, 'first_name');
        $lastName = extractValue($data, 'last_name');

        return new User($userId, $firstName, $lastName);
    }
}
