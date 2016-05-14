<?php

namespace EntityWranglerTest\Magic;

use EntityWrangler\Query\Query;
use EntityWrangler\SafeAccess;
use EntityWranglerTest\Table\IssueTable;
use EntityWranglerTest\Table\UserTable;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWranglerTest\Table\QueriedUserTable;
use EntityWranglerTest\Table\QueriedIssueTable;

use EntityWrangler\EntityTable;
use EntityWranglerTest\EntityDescription\EmailAddress;
use EntityWranglerTest\EntityDescription\User;
use EntityWranglerTest\EntityDescription\Issue;
use EntityWranglerTest\EntityDescription\IssueComment;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use EntityWranglerTest\ModelComposite\UserWithIssuesWithComments;
use EntityWranglerTest\Model\UserWithIssues;
use Zend\Hydrator\Aggregate\AggregateHydrator;

use EntityWranglerTest\TableGateway\IssueTableGateway;
use EntityWranglerTest\TableGateway\IssueCommentTableGateway;
use EntityWranglerTest\TableGateway\UserTableGateway;
use EntityWranglerTest\TableGateway\UserIssueTableGateway;
use EntityWranglerTest\EntityFactory\AllKnownEntityFactory;

class MoreMagic extends MagicQuery
{
    use SafeAccess;

    /** @var IssueTable */
    protected $issueTable;

    /** @var UserTable  */
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
    
    public function createUser($firstName, $lastName)
    {
        $data['firstName'] = $firstName;
        $data['lastName'] = $lastName;
        $this->insertIntoMappedTable($this->userTable, $data);
    }

    public function getAllAsUserWithIssues()
    {
        $this->buildQuery();
        $statement = $this->dbalQueryBuilder->execute();
        $contentArray = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $issueTableQueried = $this->queriedTables['issueTableQueried'];
        $userTableQueried = $this->queriedTables['userTableQueried'];

        $entityFactory = new AllKnownEntityFactory();
        $issueTableGateway = IssueTableGateway::fromResultSet(
            $entityFactory,
            $contentArray,
            $issueTableQueried->getAlias()
        );

        $userTableGateway = UserTableGateway::fromResultSet(
            $entityFactory,
            $contentArray,
            $userTableQueried->getAlias()
        );

        $userIssueTableGateway = UserIssueTableGateway::fromResultSet(
            $issueTableGateway,
            $userTableGateway,
            $contentArray
        );

        $userWithIssuesArray = $userIssueTableGateway->fetchAll();

        return $userWithIssuesArray;
    }
}
