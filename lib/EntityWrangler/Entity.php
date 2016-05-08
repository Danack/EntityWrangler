<?php

namespace EntityWrangler;

use EntityWrangler\EntityDefinition;
use EntityWrangler\Definition\Field;

class Entity
{
    use SafeAccess;

    /** @var string */
    private $name;
    
    /** @var string */
    private $schemaName;
    /** @var  Field[] */
    private $fields;
    private $relations;
    private $indexes;
    
    public $indexColumns = array();

    public $objectName = null;

    /**
     * @param $name
     * @param $schemaName
     * @param $fields Field[]
     * @param $relations
     * @param $indexes
     */
    public function __construct(
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

    public static function createFromDefinition(EntityDefinition $definition)
    {
        $fields = [];
        $fields[] = new Field(
            snakify($definition->getName()).'_id',
            'primary',
            'Primary key',
            ['autoInc' => true]
        );

        $fields = array_merge($fields, $definition->getFields());
        $instance = new static(
            $definition->getName(),
            'test',
            $fields,
            $definition->getRelations(),
            $definition->getIndexes()
        );
        
        return $instance;
    }

    function getRelations()
    {
        return $this->relations;
    }
    
    function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getFields()
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
     * @return bool
     */
    function getPrimaryColumn()
    {
        foreach($this->fields as $field){
            if($field->type === 'primary'){
                return $field->getName();
            }
        }
        throw new EntityWranglerException("Entity has no primary column");
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
     * @throws \Exception
     */
    function getDataTypeForColumn($columnNameToFind, $arrayOrValue)
    {
        if (!array_key_exists($columnNameToFind, $this->fields)) {
            throw new EntityWranglerException("Failed to find columnName [$columnNameToFind] in tableMap: ".$this->schemaName.".".$this->name." Fields are: ".implode(', ', array_keys($this->fields)));
        }

       return $this->fields[$columnNameToFind]->getDataTypeForColumn($arrayOrValue);
    }
}