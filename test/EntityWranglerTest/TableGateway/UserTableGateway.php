<?php

namespace EntityWranglerTest\TableGateway;

use EntityWranglerTest\Model\User;
use Zend\Hydrator\Aggregate\AggregateHydrator;


function getPrefixedData($data, $prefix)
{
    $values = [];
    
    foreach ($data as $key => $value) {
        if (strpos($key, $prefix) === 0) {
            $values[substr($key, strlen($prefix) + 1)] = $value;
        }
    }
    
    return $values;
}


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
    
    public function fetchAll()
    {
        $users = [];
        $user = new \EntityWranglerTest\Model\User();
        foreach ($this->data as $content) {
            $values = getPrefixedData($content, $this->prefix);
            $users[] = $this->aggregateHydrator->hydrate($values, $user);
        }
        
        return $users;
    }
}
