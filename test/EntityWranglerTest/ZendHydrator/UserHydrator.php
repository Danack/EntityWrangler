<?php

namespace EntityWranglerTest\ZendHydrator;

use Zend\Hydrator\HydratorInterface;
use EntityWranglerTest\Model\User;
use EntityWranglerTest\Hydrator\HydratorException;

function extractValue(array $data, $keyName)
{
    if (array_key_exists($keyName, $data) === true) {
        return $data[$keyName];
    }

    throw new HydratorException("Missing key '$keyName' in data ".var_export($data, true));
}

class UserHydrator implements HydratorInterface
{
    public function hydrate(array $data, $user)
    {
        if (!$user instanceof User) {
            throw new \Exception("Well that's messed up.");
        }
        
        //$user = new User();
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
