<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\EntityTable;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedTable;

class IssuePriorityTable extends EntityTable
{

    /**
     * Primary key
     *
     * @return string
     */
    public function columnNameIssuePriorityId()
    {
        return 'issue_priority_id';
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


}
