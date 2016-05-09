<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\Entity;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedEntity;

class QueriedUserTable extends QueriedEntity
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
