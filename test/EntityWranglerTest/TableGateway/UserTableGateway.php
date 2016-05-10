<?php

namespace EntityWranglerTest\TableGateway;

use EntityWranglerTest\Model\User;
use Zend\Hydrator\Aggregate\AggregateHydrator;


class UserTableGateway
{
    private $data;
    private $prefix;
    
    /** @var  AggregateHydrator */
    private $aggregateHydrator;



    public static function fromResultSet(

        AggregateHydrator $aggregateHydrator,
        array $data,
        $prefix
    ) {
        $instance = new self();
        $instance->aggregateHydrator = $aggregateHydrator;
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
        $user = new \EntityWranglerTest\Model\User();
        foreach ($this->data as $content) {
            $values = getPrefixedData($content, $this->prefix);
            $user = $this->aggregateHydrator->hydrate($values, $user);
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

}
