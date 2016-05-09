<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\Entity;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedEntity;

class QueriedIssuePriorityTable extends QueriedEntity
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
