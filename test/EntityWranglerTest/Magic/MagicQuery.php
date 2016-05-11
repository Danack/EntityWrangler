<?php 

namespace EntityWranglerTest\Magic;

use EntityWrangler\Query\Query;
use EntityWrangler\SafeAccess;
use EntityWranglerTest\Table\IssueTable;
use EntityWranglerTest\Table\UserTable;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWranglerTest\Table\QueriedUserTable;
use EntityWranglerTest\Table\QueriedIssueTable;

class MagicQuery extends Query
{
    use SafeAccess;

    protected $issueTable;

    protected $userTable;

    /** @var \EntityWrangler\Query\QueriedEntity[] */
    protected $queriedTables = [];

    /**
     * Join the emailAddressTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function emailAddressTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        return $this->table($this->emailAddressTable, QueriedEmailAddressTable::class, $joinEntity);
    }




    /**
     * Join the issueTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function issueTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        $issueTableQueried = $this->table($this->issueTable, QueriedIssueTable::class, $joinEntity);
        $this->queriedTables['issueTableQueried'] = $issueTableQueried;

        return $issueTableQueried;
    }

    /**
     * Join the issueCommentTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function issueCommentTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        return $this->table($this->issueCommentTable, QueriedIssueCommentTable::class, $joinEntity);
    }

    /**
     * Join the issuePriorityTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function issuePriorityTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        return $this->table($this->issuePriorityTable, QueriedIssuePriorityTable::class, $joinEntity);
    }

    /**
     * Join the userTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function userTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        $userTableQueried = $this->table($this->userTable, QueriedUserTable::class, $joinEntity);
        $this->queriedTables['userTableQueried'] = $userTableQueried;

        return $userTableQueried;
    }


}
