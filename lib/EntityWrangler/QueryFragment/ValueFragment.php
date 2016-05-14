<?php

namespace EntityWrangler\QueryFragment;

use EntityWrangler\QueryFragment;

class ValueFragment extends SQLFragment {

    var $name;
    var $value;

    function __construct($name, $value) {
        $this->name = $name;
        $this->value = $value;
    }
    
    public function insertBit(Query $query) { }
}
