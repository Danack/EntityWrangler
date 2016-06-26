<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTableDefinition;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class UserTable extends EntityTableDefinition
{

    /**
     * Primary key
     *
     * @return string
     */
    public function columnNameUserId()
    {
        return 'user_id';
    }

    /**
     * The user's first name
     *
     * @return string
     */
    public function columnNameFirstName()
    {
        return 'first_name';
    }

    /**
     * The user's last name
     *
     * @return string
     */
    public function columnNameLastName()
    {
        return 'last_name';
    }


}
