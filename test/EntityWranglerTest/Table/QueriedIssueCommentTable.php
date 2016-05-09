<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\Entity;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedEntity;

class QueriedIssueCommentTable extends QueriedEntity
{

    /**
     * blah blah.
     */
    public function whereUserIdEquals($string)
    {
        return $this->whereColumn("userId", $string);
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
