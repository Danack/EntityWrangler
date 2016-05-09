<?php


namespace EntityWranglerTest\Table;

use EntityWrangler\Query\QueriedEntity;
use EntityWrangler\Definition\Field;

class QueriedUserTable extends QueriedEntity
{
    public $user_id;
    
    public $first_name;
    
    public $last_name;
    
    public function whereFirstNameEquals($string)
    {
        $this->whereColumn('firstName', $string);//, Field::DATA_TYPE_STRING);
    }
}
