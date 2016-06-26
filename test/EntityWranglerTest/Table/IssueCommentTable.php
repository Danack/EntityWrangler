<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTableDefinition;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class IssueCommentTable extends EntityTableDefinition
{

    /**
     * Foreign key to User
     *
     * @return string
     */
    public function columnNameUserID()
    {
        return 'user_iD';
    }

    /**
     * Foreign key to Issue
     *
     * @return string
     */
    public function columnNameIssueId()
    {
        return 'issue_id';
    }

    /**
     * Primary key
     *
     * @return string
     */
    public function columnNameIssueCommentId()
    {
        return 'issue_comment_id';
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
