<?php 

namespace EntityWranglerTest\Table;

use EntityWrangler\Entity;
use EntityWrangler\Query\Query;
use EntityWrangler\Query\QueriedEntity;

class IssueTable extends Entity
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
        return 'IssueId';
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
