<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTable;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class QueriedIssuePriorityTable extends QueriedTable
{

    /**
     * blah blah.
     */
    public function whereIssuePriorityIdEquals($string)
    {
        return $this->whereColumn("issuePriorityId", $string);
    }

    /**
     * blah blah.
     */
    public function whereDescriptionEquals($string)
    {
        return $this->whereColumn("description", $string);
    }


}
