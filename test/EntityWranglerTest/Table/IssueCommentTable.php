<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTable;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class IssueCommentTable extends EntityTable
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
     * Foreign key to Issue
     *
     * @return string
     */
    public function columnNameIssueId()
    {
        return 'issueId';
    }

    /**
     * Primary key
     *
     * @return string
     */
    public function columnNameIssueCommentId()
    {
        return 'IssueCommentId';
    }

    /**
     * The text of the comment
     *
     * @return string
     */
    public function columnNameText()
    {
        return 'text';
    }


}
