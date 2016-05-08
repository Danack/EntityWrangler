<?php

namespace EntityWranglerTest;

use EntityWrangler\Entity;
use EntityWrangler\EntityBuilder;
use EntityWrangler\EntityWranglerException;


class EntityList
{
    /** @var \EntityWrangler\EntityDefinition[] */
    private $entityDefinitions = [];

    private $entityMap = [];

    public function __construct()
    {
        $this->entityDefinitions[] = \EntityWranglerTest\EntityDescription\User::class;
        $this->entityDefinitions[] = \EntityWranglerTest\EntityDescription\EmailAddress::class;
    }
 
    public function getEntity($entityName)
    {
        if (array_key_exists($entityName, $this->entityMap)) {
            return $this->entityMap[$entityName];
        }

        if (array_key_exists($entityName, $this->entityDefinitions) == false) {
            throw new EntityWranglerException("Unknown entity '$entityName'");
        }

        $entityDefinition = $this->entityDefinitions[$entityName];
        $entity = new Entity(
            $entityDefinition->getName(),
            'test',
            $entityDefinition->getFields(),
            $entityDefinition->getRelations(),
            $entityDefinition->getIndexes()
        );
        $this->entityMap[$entityName] = $entity;
        
        return $entity;
    }
}
