<?php

namespace EntityWrangler\Fragment;

use EntityWrangler\Query\Query;
use EntityWrangler\QueryFragment;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;

class OffsetFragment implements QueryFragment {

    private $offset;

    public function limitBit(Query $query) {}
    public function selectBit(Query $query) { }
    public function tableBit(Query $query) { }
    public function joinBit(Query $query) { }
    public function onBit(Query $query) { }
    public function orderBit(Query $query) { }
    public function randBit(Query $query, &$tableMap) { }
    public function whereBit(Query $query) { }
    
    public function __construct($offset)
    {
        $this->offset = $offset;
    }
    
    public function offsetBit(Query $query) { 
        $fn = function (DBALQueryBuilder $queryBuilder) {
            return $queryBuilder->setFirstResult($this->offset);
        };

        return $fn;
    }
}

