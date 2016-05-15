<?php 

namespace EntityWranglerTest\Magic;

use EntityWrangler\Query\Query;
use EntityWrangler\SafeAccess;
use EntityWranglerTest\Table\UserTable;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWranglerTest\Table\QueriedEmailAddressTable;
use EntityWranglerTest\Table\QueriedIssueTable;
use EntityWranglerTest\Table\QueriedIssueCommentTable;
use EntityWranglerTest\Table\QueriedIssuePriorityTable;
use EntityWranglerTest\Table\QueriedUserTable;

class MagicQuery extends Query
{

    public $queriedTables = array(
        
    );

    /**
     * @var \EntityWranglerTest\Table\EmailAddressTable
     */
    protected $emailAddressTable = null;

    /**
     * @var \EntityWranglerTest\Table\IssueTable
     */
    protected $issueTable = null;

    /**
     * @var \EntityWranglerTest\Table\IssueCommentTable
     */
    protected $issueCommentTable = null;

    /**
     * @var \EntityWranglerTest\Table\IssuePriorityTable
     */
    protected $issuePriorityTable = null;

    /**
     * @var \EntityWranglerTest\Table\UserTable
     */
    protected $userTable = null;

    /**
     * Join the emailAddressTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function emailAddressTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        $queriedTable = $this->table($this->emailAddressTable, QueriedEmailAddressTable::class, $joinEntity);
        //This name is not dynamic enough - one table can be queried multiple times.
        $this->queriedTables['emailAddressTableQueried'] = $queriedTable;
        return $queriedTable;
    }

    /**
     * Join the issueTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function issueTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        $queriedTable = $this->table($this->issueTable, QueriedIssueTable::class, $joinEntity);
        //This name is not dynamic enough - one table can be queried multiple times.
        $this->queriedTables['issueTableQueried'] = $queriedTable;
        return $queriedTable;
    }

    /**
     * Join the issueCommentTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function issueCommentTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        $queriedTable = $this->table($this->issueCommentTable, QueriedIssueCommentTable::class, $joinEntity);
        //This name is not dynamic enough - one table can be queried multiple times.
        $this->queriedTables['issueCommentTableQueried'] = $queriedTable;
        return $queriedTable;
    }

    /**
     * Join the issuePriorityTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function issuePriorityTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        $queriedTable = $this->table($this->issuePriorityTable, QueriedIssuePriorityTable::class, $joinEntity);
        //This name is not dynamic enough - one table can be queried multiple times.
        $this->queriedTables['issuePriorityTableQueried'] = $queriedTable;
        return $queriedTable;
    }

    /**
     * Join the userTable table.
     *
     * @param \EntityWranglerTest\Table\QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function userTable(\EntityWranglerTest\Table\QueriedUserTable $joinEntity = null)
    {
        $queriedTable = $this->table($this->userTable, QueriedUserTable::class, $joinEntity);
        //This name is not dynamic enough - one table can be queried multiple times.
        $this->queriedTables['userTableQueried'] = $queriedTable;
        return $queriedTable;
    }


}
