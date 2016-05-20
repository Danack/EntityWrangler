<?php

namespace EntityWranglerTest\Magic;

use EntityWrangler\Query\Query;
use EntityWrangler\SafeAccess;

use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;


use EntityWrangler\EntityTable;
use EntityWranglerTest\EntityDefinition\EmailAddressDefinition;
use EntityWranglerTest\EntityDefinition\UserDefinition;
use EntityWranglerTest\EntityDefinition\IssueDefinition;
use EntityWranglerTest\EntityDefinition\IssueCommentDefinition;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use EntityWranglerTest\ModelComposite\UserWithIssuesWithComments;
use EntityWranglerTest\ModelComposite\UserWithIssues;

use EntityWranglerTest\EntityFactory\AllKnownEntityFactory;
use EntityWranglerTest\Magic\MagicQuery;
use EntityWranglerTest\TableGateway\EmailAddressTableGateway;
use EntityWranglerTest\TableGateway\IssueTableGateway;
use EntityWranglerTest\TableGateway\IssueCommentTableGateway;
use EntityWranglerTest\TableGateway\UserEmailAddressTableGateway;
use EntityWranglerTest\TableGateway\UserTableGateway;
use EntityWranglerTest\TableGateway\UserIssueTableGateway;


use EntityWranglerTest\Model\EmailAddress;
use EntityWranglerTest\Model\Issue;
use EntityWranglerTest\Model\IssueComment;
use EntityWranglerTest\Model\IssuePriority;
use EntityWranglerTest\Model\User;


use EntityWranglerTest\Table\EmailAddressTable;
use EntityWranglerTest\Table\IssueTable;
use EntityWranglerTest\Table\IssueCommentTable;
use EntityWranglerTest\Table\IssuePriorityTable;
use EntityWranglerTest\Table\UserTable;


use EntityWranglerTest\Table\QueriedEmailAddressTable;
use EntityWranglerTest\Table\QueriedIssueTable;
use EntityWranglerTest\Table\QueriedIssueCommentTable;
use EntityWranglerTest\Table\QueriedIssuePriorityTable;
use EntityWranglerTest\Table\QueriedUserTable;

class MoreMagic extends MagicQuery
{
    use SafeAccess;

    private $magicQuery;
    
    public function __construct(
        AllKnownEntityFactory $entFactory,
        DBALQueryBuilder $dbalQueryBuilder,
        EmailAddressTable $emailAddressTable,
        IssueTable $issueTable,
        IssuePriorityTable $issuePriorityTable,
        UserTable $userTable
    ) {
        parent::__construct($dbalQueryBuilder);
        $this->emailAddressTable = $emailAddressTable;
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
    
    public function saveEmailAddress(EmailAddress $emailAddress)
    {
        $data = $emailAddress->toData();
        
        $this->insertIntoMappedTable($this->emailAddressTable, QueriedEmailAddressTable::class, $data);
    }

    public function saveIssue(Issue $issue)
    {
        $data = $issue->toData();

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
    
    public function getAllAsUserWithEmailAddress()
    {
        $contentArray = $this->fetch();
        $userTableQueried = $this->queriedTables['userTableQueried'];
        $emailAddressTableQueried = $this->queriedTables['emailAddressTableQueried'];

        $entityFactory = new AllKnownEntityFactory();

        //$entityFactory->create()
        $emailAddressTableGateway = EmailAddressTableGateway::fromResultSet(
            $entityFactory,
            $contentArray,
            $emailAddressTableQueried->getAlias()
        );

        $userTableGateway = UserTableGateway::fromResultSet(
            $entityFactory,
            $contentArray,
            $userTableQueried->getAlias()
        );

        $userIssueTableGateway = UserEmailAddressTableGateway::fromResultSet(
            $emailAddressTableGateway,
            $userTableGateway,
            $contentArray
        );

        $userWithEmailArray = $userIssueTableGateway->fetchAll();

        return $userWithEmailArray;
    }
    
    
    
    public function getAllAsUserWithIssues()
    {
//        $this->buildQuery(self::QUERY_TYPE_SELECT);
////        $sql = $this->dbalQueryBuilder->getSQL();
//        
//        $statement = $this->dbalQueryBuilder->execute();
//        $contentArray = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $contentArray = $this->fetch();

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
