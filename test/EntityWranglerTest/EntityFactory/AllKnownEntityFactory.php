<?php

namespace EntityWranglerTest\EntityFactory;

use EntityWrangler\EntityWranglerException;
use EntityWranglerTest\EntityFactory\UserFactory;
use EntityWranglerTest\EntityFactory\IssueFactory;
use EntityWranglerTest\EntityFactory\IssueCommentFactory;

class AllKnownEntityFactory
{
    /** @var \EntityWranglerTest\EntityFactory[] */
    private $entityFactories = [];
    
    public function __construct()
    {
        $this->entityFactories['EntityWranglerTest\Model\Issue'] = new IssueFactory();
        $this->entityFactories['EntityWranglerTest\Model\IssueComment'] = new IssueCommentFactory();
        $this->entityFactories['EntityWranglerTest\Model\User'] = new UserFactory();
    }
    
    public function create(array $data, $type)
    {
        if (array_key_exists($type, $this->entityFactories) === true) {
            $entityFactory = $this->entityFactories[$type];
            return $entityFactory->create($data);
        }

        $reflClass = new \ReflectionClass($type);
        if ($reflClass->hasMethod('fromData') === true) {
            return $type::fromData($data);
        }

        throw new EntityWranglerException("No factory registered for type '$type'.");
    }
    
    
    
    
}
