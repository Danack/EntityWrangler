<?php


namespace EntityWrangler;

use Doctrine\DBAL\Schema\Schema;
use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\EntityIdentity;


class SchemaBuilder
{
    /** @var Schema */
    public $toSchema;
    
    /** @var EntityDefinition[] */
    private $knownEntities = [];
    
    public function __construct(Schema $toSchema)
    {
        $this->toSchema = $toSchema;
    }
    
    public function addEntityDefinition(EntityDefinition $entityDefinition)
    {
        $this->knownEntities[] = $entityDefinition;
    }

    /**
     * @return GeneratedSchema
     */
    public function build()
    {
        return new GeneratedSchema($this);
    }

    /**
     * @return EntityDefinition[]
     */
    public function getKnownEntities()
    {
        return $this->knownEntities;
    }
}
