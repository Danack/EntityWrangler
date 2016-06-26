<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTableDefinition;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class QueriedEmailAddressTable extends QueriedTable
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
    public function whereEmailAddressIdEquals($string)
    {
        return $this->whereColumn("emailAddressId", $string);
    }

    /**
     * blah blah.
     */
    public function whereAddressEquals($string)
    {
        return $this->whereColumn("address", $string);
    }


}
