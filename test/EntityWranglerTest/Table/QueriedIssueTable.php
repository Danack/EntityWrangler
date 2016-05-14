<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTable;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class QueriedIssueTable extends QueriedTable
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
    public function whereDescriptionEquals($string)
    {
        return $this->whereColumn("description", $string);
    }

    /**
     * blah blah.
     */
    public function whereTextEquals($string)
    {
        return $this->whereColumn("text", $string);
    }


}
