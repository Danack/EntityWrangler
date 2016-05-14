<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTable;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class UserTable extends EntityTable
{

    /**
     * Primary key
     *
     * @return string
     */
    public function columnNameUserId()
    {
        return 'UserId';
    }

    /**
     * The user's first name
     *
     * @return string
     */
    public function columnNameFirstName()
    {
        return 'firstName';
    }

    /**
     * The user's last name
     *
     * @return string
     */
    public function columnNameLastName()
    {
        return 'lastName';
    }


}
