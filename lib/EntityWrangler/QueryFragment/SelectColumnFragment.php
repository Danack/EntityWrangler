<?php

namespace EntityWrangler\QueryFragment;

use EntityWrangler\Query\Query;
use EntityWrangler\QueryFragment;
use EntityWrangler\EntityTableDefinition;


class SelectColumnFragment implements QueryFragment
{

    /**
     * @var EntityTableDefinition
     */
    var $tableMap;

    var $column;

    public function insertBit(Query $query) { }
    public function joinBit(Query $query) { }
    public function offsetBit(Query $query) { }
    public function onBit(Query $query) { }
    public function orderBit(Query $query) { }
    public function randBit(Query $query, &$tableMap) { }
    public function selectBit(Query $query) { }
    public function tableBit(Query $query) { }
    public function whereBit(Query $query) { }
    public function limitBit(Query $query) { }
    
    function __construct(EntityTableDefinition $tableMap, $column) {
        $this->tableMap = $tableMap;
        $this->column = $column;
    }
}
