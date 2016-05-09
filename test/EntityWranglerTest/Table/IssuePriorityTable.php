<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\Entity;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedEntity;

class IssuePriorityTable extends Entity
{

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameIssuePriorityId()
    {
        return 'IssuePriorityId';
    }

    /**
     * blah blah.
     *
     * @return string
     */
    public function columnNameDescription()
    {
        return 'description';
    }


}
