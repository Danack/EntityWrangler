<?php

namespace EntityWrangler\Fragment;

use EntityWrangler\Query\Query;
use EntityWrangler\QueryFragment;
use EntityWrangler\Entity;
use EntityWrangler\QueryFragment;

class SelectColumnFragment implements QueryFragment {

    /**
     * @var Entity
     */
    var $tableMap;

    var $column;
    
    public function joinBit(Query $query) { }
    public function offsetBit(Query $query) { }
    public function onBit(Query $query) { }
    public function randBit(Query $query, &$tableMap) { }
    public function whereBit(Query $query) { }
    public function limitBit(Query $query) { }
    
    function __construct(Entity $tableMap, $column) {
        $this->tableMap = $tableMap;
        $this->column = $column;
    }
}
