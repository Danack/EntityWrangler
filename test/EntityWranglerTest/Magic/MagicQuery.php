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

    public function __construct(
        DBALQueryBuilder $dbalQueryBuilder,
        IssueTable $issueTable,
        UserTable $userTable
    ) {
        parent::__construct($dbalQueryBuilder);
        $this->issueTable = $issueTable;
        $this->userTable = $userTable;
    }

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
        return $this->table($this->issueTable, QueriedIssueTable::class, $joinEntity);
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
        return $this->table($this->userTable, QueriedUserTable::class, $joinEntity);
    }


}
