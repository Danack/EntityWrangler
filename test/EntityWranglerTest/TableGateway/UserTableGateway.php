<?php

namespace EntityWranglerTest\TableGateway;

use EntityWranglerTest\Model\User;
use EntityWranglerTest\EntityFactory\AllKnownEntityFactory;
use EntityWranglerTest\Magic\MoreMagic;

class UserTableGateway
{
    private $data;
    private $prefix;
    
    /** @var  AllKnownEntityFactory */
    private $allKnownEntityFactory;

    public static function fromResultSet(
        AllKnownEntityFactory $allKnownEntityFactory,
        array $data,
        $prefix
    ) {
        $instance = new self();
        $instance->allKnownEntityFactory = $allKnownEntityFactory;
        $instance->data = $data;
        $instance->prefix = $prefix;

        return $instance;
    }

    /**
     * @return \EntityWranglerTest\Model\User[]
     */
    public function fetchAll()
    {
        $users = [];
        foreach ($this->data as $content) {
            $values = getPrefixedData($content, $this->prefix);

            $user = $this->allKnownEntityFactory->create(
                $values,
                User::class
            );

            $users[$user->userId] = $user;
        }
        
        return $users;
    }

    public function filterDataByUserId(array $data, $userID)
    {
        $filteredData = [];
        $key = $this->prefix.'_user_id';

        foreach ($data as $row) {
            if ($row[$key] == $userID) {
                $filteredData[] = $row;
            }
        }

        return $filteredData;
    }

    
    public function createUser(MoreMagic $moreMagic, $firstName, $lastName)
    {
        $moreMagic->insert();
    }
}
