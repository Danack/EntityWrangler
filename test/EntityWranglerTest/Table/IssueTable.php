<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTableDefinition;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class IssueTable extends EntityTableDefinition
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
    public function columnNameIssueId()
    {
        return 'issue_id';
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
