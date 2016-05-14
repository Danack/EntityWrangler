<?php

namespace EntityWrangler\QueryFragment;

use EntityWrangler\QueryFragment\BindableParams;
use EntityWrangler\QueriedTable;
use EntityWrangler\SQLQuery;
use EntityWrangler\QueryFragment;
use EntityWrangler\Query\Query;


class AncestorFragment implements BindableParams, QueryFragment {

    /**
     * @var QueriedTable
     */
    public $queriedTableMap;
    
    public $ancestorID;
    
    public $queriedClosureTable;

    protected $isDescendant = false;
    
    public function limitBit(Query $query) { }
    public function offsetBit(Query $query) { }
    public function orderBit(Query $query) { }

    function __construct(QueriedTable $queriedTable, QueriedTable $queriedClosureTable, $ancestorID, $isDescendant = false) {
        $this->queriedTableMap = $queriedTable;
        $this->ancestorID = $ancestorID;
        $this->queriedClosureTable = $queriedClosureTable;
        $this->isDescendant = $isDescendant;
    }

    public function insertBit(Query $query) {}
    
    function &getValue() {
        return $this->ancestorID;
    }

    function getType() {
        return 'i';
    }

    /**
     * @return \EntityWrangler\QueriedTable
     */
    public function getQueriedTableMap() {
        return $this->queriedTableMap;
    }

    /**
     * @param SQLQuery $sqlQuery
     */
    function joinBit(SQLQuery $sqlQuery) {
        $closureTable = $this->queriedClosureTable;
        $alias = $this->queriedClosureTable->getAlias();
        $sqlQuery->addSQL("join ".$closureTable->getSchema().".".$closureTable->getTableName()." as $alias");
    }

    /**
     * @param SQLQuery $sqlQuery
     */
    function onBit(SQLQuery $sqlQuery) {
        $tableMap = $this->queriedTableMap->getTableMap();
        $alias = $this->queriedTableMap->getAlias();
        $closureAlias = $this->queriedClosureTable->getAlias();
        if ($this->isDescendant == true) {
            $sqlQuery->addSQL("on (".$alias.".".$tableMap->getPrimaryColumn()." = $closureAlias.descendant)");
        }
        else {
            $sqlQuery->addSQL("on (".$alias.".".$tableMap->getPrimaryColumn()." = $closureAlias.ancestor)");
        }
    }




    /**
     * @param SQLQuery $sqlQuery
     */
    function whereBit(SQLQuery $sqlQuery)
    {
        $alias = $this->queriedClosureTable->getAlias();

        if ($this->isDescendant == true) {
            $sqlQuery->addSQL(" $alias.ancestor = ?");
        }
        else { 
            $sqlQuery->addSQL(" $alias.descendant = ?");
        }
    }    
}
