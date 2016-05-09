<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\Entity;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedEntity;

class IssueCommentTable extends Entity
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
    public function columnNameIssueId()
    {
        return 'issueId';
    }

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameIssueCommentId()
    {
        return 'IssueCommentId';
    }

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameText()
    {
        return 'text';
    }


}
