<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\Entity;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedEntity;

class EmailAddressTable extends Entity
{

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameUserId()
    {
        return 'userId';
    }

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameEmailAddressId()
    {
        return 'EmailAddressId';
    }

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameAddress()
    {
        return 'address';
    }


}
