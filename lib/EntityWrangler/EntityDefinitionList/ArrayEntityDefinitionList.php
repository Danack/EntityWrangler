<?php

namespace EntityWrangler\EntityDefinitionList;

use EntityWrangler\EntityDefinitionList;


class ArrayEntityDefinitionList implements EntityDefinitionList
{
    /**
     * @var \EntityWrangler\EntityDefinition[]
     */
    private $entityDefinition;
    
    function __construct(array $entityDefinition)
    {
        $this->entityDefinition = $entityDefinition; 
    }

    /**
     * @return \EntityWrangler\EntityDefinition[]
     */
    public function getEntityDefinitions()
    {
        return $this->entityDefinition;
    }
}
