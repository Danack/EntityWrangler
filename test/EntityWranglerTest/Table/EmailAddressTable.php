<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTable;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class EmailAddressTable extends EntityTable
{

    /**
     * Foreign key to User
     *
     * @return string
     */
    public function columnNameUserId()
    {
        return 'user_id';
    }

    /**
     * Primary key
     *
     * @return string
     */
    public function columnNameEmailAddressId()
    {
        return 'email_address_id';
    }

    /**
     * The email address
     *
     * @return string
     */
    public function columnNameAddress()
    {
        return 'address';
    }


}
