<?php

namespace EntityWrangler\EntityDefinitionList;

use EntityWrangler\EntityDefinitionList;
use EntityWrangler\EntityDefinition;


class CompositeEntityDefinitionList implements EntityDefinitionList
{
    private $entityDefinitionListArray;
    
    public function __construct(array $entityDefinitionListArray)
    {
        $fn = function (EntityDefinitionList ...$entityDefinitionList) {
            return $entityDefinitionList;
        };

        $this->entityDefinitionListArray = $fn(...$entityDefinitionListArray);
    }

    /**
     * @return \EntityWrangler\EntityDefinition[]
     */
    public function getEntityDefinitions()
    {
        // TODO: Implement getEntityDefinitions() method.
    }
}
