<?php

namespace EntityWrangler\Definition;

use EntityWrangler\Definition\TableColumn;
use EntityWrangler\EntityDefinition;

class TableColumns
{
    private $columns = [];
    
    public static function fromDefinition(EntityDefinition $entityDefinition)
    {
        $instance = new self();
        $idName = $entityDefinition->getTableInfo()->tableName.'Id';
        $instance->columns[] = new TableColumn(
            $idName,
            'int',
            'Primary key',
            snakify($idName)
        );

        foreach ($entityDefinition->getFields() as $field) {
            $instance->columns[] = new TableColumn(
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
