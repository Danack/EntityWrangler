<?php

namespace EntityWranglerTest\ZendHydrator;

use Zend\Hydrator\HydratorInterface;
use EntityWranglerTest\Model\User;
use EntityWranglerTest\Hydrator\HydratorException;


class UserHydrator implements HydratorInterface
{
    public function hydrate(array $data, $user)
    {
        if (!$user instanceof User) {
            // Nothing to do.
            return $user;
        }

        $user->userId = extractValue($data, 'user_id');
        $user->firstName = extractValue($data, 'first_name');
        $user->lastName = extractValue($data, 'last_name');

        return $user;
    }
    
    public function extract($object)
    {
        throw new \Exception("not implemented.");
    }
}
