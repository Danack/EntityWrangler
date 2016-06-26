<?php

namespace EntityWrangler;

use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\EntityProperty;

/**
 * Class EntityTable
 * 
 * The description of how an Entity is stored in a database.
 */
class EntityTableDefinition
{
    use SafeAccess;

    /** @var string */
    private $name;
    
    /** @var string */
    private $schemaName;

    /** @var  EntityProperty[] */
    private $fields;
    
    private $relations;
    
    private $indexes;
    
    public $indexColumns = array();

    public $objectName = null;

    /**
     * 
     * You almost certainly don't want to call this directly, instead go through 
     * the createFromDefinition factory method.
     * 
     * @param $name
     * @param $schemaName
     * @param $fields EntityProperty[]
     * @param $relations
     * @param $indexes
     */
    protected function __construct(
        $name,
        $schemaName,
        $fields,
        $relations,
        $indexes
    ) {
        $this->name = $name;
        $this->schemaName = $schemaName;
        
        $this->fields = [];
        foreach ($fields as $field) {
            $this->fields[$field->getName()] = $field;
        }

        $this->relations = $relations; 
        $this->indexes = $indexes;
    }
    
    public static function createExtraTables(EntityDefinition $definition)
    {
        $tables = [];
        
        if ($definition->isTree() == true) {
            $tableInfo = $definition->getTableInfo();
            $tableName = $tableInfo->tableName.'Tree';
            $fields = [];
            $fields[] = new EntityProperty(
                lcfirst($tableInfo->tableName).'Id',
                'identity',
                'Identity key',
                snakify($tableInfo->tableName).'_id'
            );
            
            $instance = new static(
                $tableName,
                $tableInfo->schemaName,
                $fields,
                $definition->getRelations(),
                $definition->getIndexes()
            );
        }
        
        return $tables;
    }

    public static function createFromDefinition(EntityDefinition $definition)
    {
        $fields = [];
        $tableInfo = $definition->getTableInfo();
        $fields[] = new EntityProperty(
            lcfirst($tableInfo->tableName).'Id',
            'identity',
            'Identity key',
            snakify($tableInfo->tableName).'_id'
        );

        $fields = array_merge($fields, $definition->getProperties());
        $instance = new static(
            $tableInfo->tableName,
            $tableInfo->schemaName,
            $fields,
            $definition->getRelations(),
            $definition->getIndexes()
        );
        
        return $instance;
    }

    /** @return \EntityWrangler\Definition\EntityRelation[] */
    function getRelations()
    {
        return $this->relations;
    }
    
    function getName()
    {
        return $this->name;
    }

    /**
     * @return EntityProperty[]
     */
    public function getProperties()
    {
        return $this->fields;
    }

    /**
     * @return mixed
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @return mixed
     */
    public function getSchemaName()
    {
        return $this->schemaName;
    }

    /**
     * @return string
     */
    function getDTONamespace()
    {
        return $this->dtoNamespace;
    }

    /**
     * @return string
     */
    function getDTOClassname()
    {
        return ucfirst($this->getName())."DTO";
    }

    /**
     * @return bool
     */
    function isTreeLike()
    {
        return false;
    }

    /**
     * @return string
     */
    function getIdentityColumnName()
    {
        foreach($this->fields as $field) {
            if ($field->type === 'identity') {
                return $field->getDBName();
            }
        }
        throw new EntityWranglerException("Entity has no primary column.");
    }
    

    

//    /**
//     * @return Relation|null
//     */
//    function getSelfClosureRelation() {
//        foreach ($this->relations as $relation) {
//            if ($relation->getType() == Relation::SELF_CLOSURE) {
//                return $relation;
//            }
//        }
//        
//        return null;
//    }

    /**
     * @param $columnNameToFind
     * @param $arrayOrValue
     * @return bool|string
     */
    function getDataTypeForColumn($columnNameToFind, $arrayOrValue)
    {
        if (!array_key_exists($columnNameToFind, $this->fields)) {
            $message = sprintf(
                "Failed to find columnName [%s] in tableMap: %s.%s Fields are: %s",
                $columnNameToFind,
                $this->schemaName,
                $this->name,
                implode(', ', array_keys($this->fields))
            );
            
            throw new EntityWranglerException($message);
        }

       return $this->fields[$columnNameToFind]->getDataTypeForColumn($arrayOrValue);
    }
}