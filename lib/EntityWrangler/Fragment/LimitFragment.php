<?php

namespace EntityWrangler\Fragment;

use EntityWrangler\Query\Query;
use EntityWrangler\QueryFragment;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;

class LimitFragment implements QueryFragment {

    private $limit;

    function __construct($limit) {
        $this->limit = $limit;
    }

    public function selectBit(Query $query) { }
    public function tableBit(Query $query) { }
    public function joinBit(Query $query) { }
    public function offsetBit(Query $query) { }
    public function onBit(Query $query) { }
    public function orderBit(Query $query) { }
    public function randBit(Query $query, &$tableMap) { }
    public function whereBit(Query $query) { }
    
    public function limitBit(Query $query) {
        $fn = function (DBALQueryBuilder $queryBuilder) {
            return $queryBuilder->setMaxResults($this->limit);
        };

        return $fn;
    }
}
