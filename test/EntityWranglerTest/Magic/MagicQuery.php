<?php 

namespace EntityWranglerTest\Magic;

use EntityWrangler\EntityWranglerException;
use EntityWrangler\Query\Query;
use EntityWrangler\SafeAccess;
use EntityWranglerTest\Table\UserTable;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWranglerTest\Table\QueriedIssueTable;
use EntityWranglerTest\Table\QueriedUserTable;
use Auryn\Injector;

class MagicQuery extends Query
{
    /** @var  \Auryn\Injector */
    private $injector;

    public function __construct(Injector $injector, DBALQueryBuilder $dbalQueryBuilder)
    {
        $this->injector = $injector;
        parent::__construct($dbalQueryBuilder);
    }
    
    public function __get($name)
    {
        if ($name === 'userTable') {
            return $this->injector->make('EntityWranglerTest\Table\UserTable');
        }
        
        if ($name === 'issueTable') {
            return $this->injector->make('EntityWranglerTest\Table\IssueTable');
        }
        
        throw new EntityWranglerException("Internal error, unknown property $name");
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
