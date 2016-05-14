<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTable;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class IssueTable extends EntityTable
{

    /**
     * Foreign key to User
     *
     * @return string
     */
    public function columnNameUserId()
    {
        return 'userId';
    }

    /**
     * Primary key
     *
     * @return string
     */
    public function columnNameIssueId()
    {
        return 'IssueId';
    }

    /**
     * The description of the issue.
     *
     * @return string
     */
    public function columnNameDescription()
    {
        return 'description';
    }

    /**
     * the text of the issue
     *
     * @return string
     */
    public function columnNameText()
    {
        return 'text';
    }


}
