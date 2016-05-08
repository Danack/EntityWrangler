<?php

namespace EntityWrangler\Definition;

use EntityWrangler\Definition\Column;
use EntityWrangler\EntityDefinition;

class TableColumns
{
    private $columns = [];
    
    public static function fromDefinition(EntityDefinition $entityDefinition)
    {
        $instance = new self();
        foreach ($entityDefinition->getFields() as $field) {
            $instance->columns[] = new Column(
                $field->getName(),
                $field->type,
                $field->description,
                snakify($field->getName())
            );
        }
        
        
        
        foreach ($entityDefinition->getRelations() as $relation) {
            $columns = $relation->getColumns();
            $instance->columns = array_merge($columns, $instance->columns);
        }

        return $instance;
    }
    
    public function getColumns()
    {
        return $this->columns;
    }
}
