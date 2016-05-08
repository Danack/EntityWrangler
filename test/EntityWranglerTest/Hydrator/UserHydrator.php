<?php

namespace EntityWranglerTest\Hydrator;

use \EntityWranglerTest\Model\User;

class UserHydrator implements Hydrator
{
    public function hydrate(array $data, HydratorRegistry $hydratorRegistry, $aliasPrefix)
    {
        $user = new User();
        $user->userId = $hydratorRegistry->extractValue($data, $aliasPrefix.'user_id');
        $user->firstName = $hydratorRegistry->extractValue($data, $aliasPrefix.'first_name');
        $user->lastName = $hydratorRegistry->extractValue($data, $aliasPrefix.'last_name');

        return $user;
    }
}
