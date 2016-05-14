<?php

namespace EntityWrangler;

use EntityWrangler\SQLQuery;
use EntityWrangler\Query\Query;

interface QueryFragment
{
    
    public function insertBit(Query $query);
    public function joinBit(Query $query);
    public function limitBit(Query $query);
    public function offsetBit(Query $query);
    public function onBit(Query $query);
    public function orderBit(Query $query);
    public function randBit(Query $query, &$tableMap);
    public function selectBit(Query $query);
    public function tableBit(Query $query);
    public function whereBit(Query $query);
}
