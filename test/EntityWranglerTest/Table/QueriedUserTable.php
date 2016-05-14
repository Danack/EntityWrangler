<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTable;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class QueriedUserTable extends QueriedTable
{

    /**
     * blah blah.
     */
    public function whereUserIdEquals($string)
    {
        return $this->whereColumn("userId", $string);
    }

    /**
     * blah blah.
     */
    public function whereFirstNameEquals($string)
    {
        return $this->whereColumn("firstName", $string);
    }

    /**
     * blah blah.
     */
    public function whereLastNameEquals($string)
    {
        return $this->whereColumn("lastName", $string);
    }


}
