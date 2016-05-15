<?php

namespace EntityWranglerTest\Magic;

use EntityWrangler\Query\Query;
use EntityWrangler\SafeAccess;
use EntityWranglerTest\Model\IssuePriority;
use EntityWranglerTest\Table\IssueTable;
use EntityWranglerTest\Table\UserTable;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWranglerTest\Table\QueriedUserTable;
use EntityWranglerTest\Table\QueriedIssueTable;
use EntityWranglerTest\Table\QueriedIssuePriorityTable;

use EntityWrangler\EntityTable;
use EntityWranglerTest\EntityDefinition\EmailAddressDefinition;
use EntityWranglerTest\EntityDefinition\UserDefinition;
use EntityWranglerTest\EntityDefinition\IssueDefinition;
use EntityWranglerTest\EntityDefinition\IssueCommentDefinition;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use EntityWranglerTest\ModelComposite\UserWithIssuesWithComments;
use EntityWranglerTest\ModelComposite\UserWithIssues;
use EntityWranglerTest\TableGateway\IssueTableGateway;
use EntityWranglerTest\TableGateway\IssueCommentTableGateway;
use EntityWranglerTest\TableGateway\UserTableGateway;
use EntityWranglerTest\TableGateway\UserIssueTableGateway;
use EntityWranglerTest\EntityFactory\AllKnownEntityFactory;
use EntityWranglerTest\Magic\MagicQuery;
use EntityWranglerTest\Table\IssuePriorityTable;

use EntityWranglerTest\Model\Issue;
use EntityWranglerTest\Model\User;



class MoreMagic extends MagicQuery
{
    use SafeAccess;

    private $magicQuery;
    
    public function __construct(
        AllKnownEntityFactory $entFactory,
        DBALQueryBuilder $dbalQueryBuilder,
        IssueTable $issueTable,
        IssuePriorityTable $issuePriorityTable,
        UserTable $userTable
    ) {
        parent::__construct($dbalQueryBuilder);
        $this->issuePriorityTable = $issuePriorityTable;
        $this->issueTable = $issueTable;
        $this->userTable = $userTable;
    }
    
    public function saveUser(User $user)
    {
        $data['user_id'] = $user->getUserId();
        $data['first_name'] = $user->getFirstName();
        $data['last_name'] = $user->getLastName();
        $this->insertIntoMappedTable($this->userTable, QueriedUserTable::class, $data);
    }

    public function saveIssue(Issue $issue)
    {
        $data['issue_priority_id'] = $issue->getIssueId();
        $data['description'] = $issue->getDescription();
        $this->insertIntoMappedTable($this->issueTable, QueriedIssueTable::class, $data);
    }

    public function saveIssuePriority(IssuePriority $issuePriority)
    {
        $data['issue_priority_id'] = $issuePriority->getIssuePriorityId();
        $data['description'] = $issuePriority->getDescription();
        $this->insertIntoMappedTable($this->issuePriorityTable, QueriedIssuePriorityTable::class, $data);
    }


    /**
     * @return \EntityWranglerTest\Model\User[]
     */
    public function fetchAsUsers()
    {
        $contentArray = $this->fetch();

        $userTableQueried = $this->queriedTables['userTableQueried'];
        $entityFactory = new AllKnownEntityFactory();
        $userTableGateway = UserTableGateway::fromResultSet(
            $entityFactory,
            $contentArray,
            $userTableQueried->getAlias()
        );
        $userArray = $userTableGateway->fetchAll();

        return $userArray;
    }
    
    
    public function getAllAsUserWithIssues()
    {
        $this->buildQuery(self::QUERY_TYPE_SELECT);
//        $sql = $this->dbalQueryBuilder->getSQL();
        
        $statement = $this->dbalQueryBuilder->execute();
        $contentArray = $statement->fetchAll(\PDO::FETCH_ASSOC);

//        $contentArray = $this->fetch();

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
