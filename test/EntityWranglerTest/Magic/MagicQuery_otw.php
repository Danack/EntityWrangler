<?php


namespace EntityWranglerTest\Magic;

use EntityWrangler\Query\Query;
use EntityWrangler\SafeAccess;
use EntityWranglerTest\Table\UserTable;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWranglerTest\Table\QueriedUserTable;

class MagicQuery extends Query
{
    use SafeAccess;
    
    /** @var UserTable  */
    private $userTable;
    
    public function __construct(UserTable $userTable, DBALQueryBuilder $dbalQueryBuilder)
    {
         parent::__construct($dbalQueryBuilder);
         $this->userTable = $userTable;
    }

    /**
     * @param QueriedUserTable $joinEntity
     * @return \EntityWranglerTest\Table\QueriedUserTable
     */
    public function userTable(QueriedUserTable $joinEntity = null)
    {
        return $this->table($this->userTable, QueriedUserTable::class, $joinEntity);
    }
}
