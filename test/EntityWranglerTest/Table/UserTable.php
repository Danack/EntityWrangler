<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\Entity;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedEntity;

class UserTable extends Entity
{

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameUserId()
    {
        return 'UserId';
    }

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameFirstName()
    {
        return 'firstName';
    }

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameLastName()
    {
        return 'lastName';
    }


}
