<?php

namespace EntityWrangler\QueryFragment;

//use EntityWrangler\QueriedTable;
use EntityWrangler\QueryFragment;

class NullFragment extends SQLFragment{

    /**
     * @var QueriedTable
     */
    var $tableMap;

    /**
     * @var QueriedTable
     */
    var $nullTableMap;
    var $columnValues = array();

    var $nullTableMapAlias;
    
    public function insertBit(Query $query) { }
    public function limitBit(Query $query) {}
    public function offsetBit(Query $query) { }

    //TODO - shouldn't be passing in alias
    function __construct(QueriedTable $tableMap, QueriedTable $nullTableMap, $nullTableAlias, $columnValues) {
        $this->tableMap = $tableMap;
        $this->nullTableMapAlias = $nullTableAlias;
        $this->nullTableMap = $nullTableMap;
        $this->columnValues = $columnValues;
    }

}


