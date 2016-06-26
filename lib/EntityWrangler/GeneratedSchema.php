<?php


namespace EntityWrangler;

use EntityWrangler\Definition\EntityIdentity;
use EntityWrangler\SchemaBuilder;

class GeneratedSchema
{
    /** @var $tableDefinitions EntityTableDefinition[] */
        private $tableDefinitions = [];
    
    /** @var \Doctrine\DBAL\Schema\Table[]  */
    private $dbTables = [];
    
    /** @var SchemaBuilder  */
    private $schemaGenerator;
    
    public function __construct(SchemaBuilder $schemaGenerator)
    {
        $this->schemaGenerator = $schemaGenerator;
        $this->init();
    }

    /**
     * @return EntityTableDefinition[]
     */
    public function getTableDefinitions()
    {
        return $this->tableDefinitions;
    }

    /**
     * @return \Doctrine\DBAL\Schema\Table[]
     */
    public function getDBTables()
    {
        return $this->dbTables;
    }
    
    /**
     * Do the work of building the schema.
     */
    private function init()
    {
        foreach ($this->schemaGenerator->getKnownEntities() as $knownEntity) {
            $this->tableDefinitions[] = EntityTableDefinition::createFromDefinition(new $knownEntity());
        }
        
        foreach ($this->tableDefinitions as $userTable) {
            $this->dbTables[] = $this->generateTableSchema($userTable);
        }
    }

    /**
     * @return \Doctrine\DBAL\Schema\Table
     */
    private function generateTableSchema(EntityTableDefinition $entityTableDefinition)
    {
        $dbTable = $this->schemaGenerator->toSchema->createTable($entityTableDefinition->getName());

        foreach ($entityTableDefinition->getProperties() as $field) {
            $type = $field->type;
            if ($field->type == 'identity') {
                $type = 'string';
            }
            $dbTable->addColumn($field->getDBName(), $type, ['length' => 255]);
        }

        foreach ($entityTableDefinition->getRelations() as $relation) {
            $type = 'string';
            $options = ['length' => 255];
            if ($relation->entityIdentity->getType() == EntityIdentity::TYPE_PRIMARY) {
                $type = 'integer';
                $options = [];
            }
            $dbTable->addColumn($relation->getDBName(), $type, $options);
        }
        
        return $dbTable;
    }
}
