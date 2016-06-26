<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTableDefinition;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class QueriedIssueCommentTable extends QueriedTable
{

    /**
     * blah blah.
     */
    public function whereUserIDEquals($string)
    {
        return $this->whereColumn("userID", $string);
    }

    /**
     * blah blah.
     */
    public function whereIssueIdEquals($string)
    {
        return $this->whereColumn("issueId", $string);
    }

    /**
     * blah blah.
     */
    public function whereIssueCommentIdEquals($string)
    {
        return $this->whereColumn("issueCommentId", $string);
    }

    /**
     * blah blah.
     */
    public function whereTextEquals($string)
    {
        return $this->whereColumn("text", $string);
    }


}
