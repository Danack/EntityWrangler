<?php

namespace EntityWranglerTest\Magic;

use EntityWrangler\Query\Query;
use EntityWrangler\SafeAccess;
use EntityWranglerTest\Table\IssueTable;
use EntityWranglerTest\Table\UserTable;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use EntityWranglerTest\Table\QueriedUserTable;
use EntityWranglerTest\Table\QueriedIssueTable;

use EntityWrangler\Entity;
use EntityWranglerTest\EntityDescription\EmailAddress;
use EntityWranglerTest\EntityDescription\User;
use EntityWranglerTest\EntityDescription\Issue;
use EntityWranglerTest\EntityDescription\IssueComment;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use EntityWranglerTest\Hydrator\HydratorRegistry;
use EntityWranglerTest\Hydrator\UserWithEmailHydrator;
use EntityWranglerTest\Hydrator\UserWithIssuesHydrator;
use EntityWranglerTest\Hydrator\UserWithIssuesWithCommentsHydrator;
use EntityWranglerTest\ModelComposite\UserWithIssuesWithComments;
use EntityWranglerTest\Hydrator\IssueWithCommentsAndUserHydrator;

use EntityWranglerTest\Model\UserWithIssues;
//use EntityWranglerTest\TableGateway\UserTableGateway;
use Zend\Hydrator\Aggregate\AggregateHydrator;

use EntityWranglerTest\TableGateway\IssueTableGateway;
use EntityWranglerTest\TableGateway\IssueCommentTableGateway;
use EntityWranglerTest\TableGateway\UserTableGateway;
use EntityWranglerTest\TableGateway\UserIssueTableGateway;


use EntityWranglerTest\ZendHydrator\IssueHydrator;
use EntityWranglerTest\ZendHydrator\UserHydrator;
use EntityWranglerTest\ZendHydrator\UserIssueHydrator;

class MoreMagic extends MagicQuery
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


    public function getAllAsUserWithIssues()
    {
        $this->buildQuery();
        $statement = $this->dbalQueryBuilder->execute();
        $contentArray = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $issueTableQueried = $this->queriedTables['issueTableQueried'];
        $userTableQueried = $this->queriedTables['userTableQueried'];

        $hydrator = new AggregateHydrator();
        $issueTableGateway = IssueTableGateway::fromResultSet(
            $hydrator,
            $contentArray,
            $issueTableQueried->getAlias()
        );

        $userTableGateway = UserTableGateway::fromResultSet(
            $hydrator,
            $contentArray,
            $userTableQueried->getAlias()
        );

        $userIssueTableGateway = UserIssueTableGateway::fromResultSet(
            $issueTableGateway,
            $userTableGateway,
            $hydrator,
            $contentArray
        );

        $hydrator->add(new IssueHydrator());
        $hydrator->add(new UserHydrator());
        $hydrator->add(new UserIssueHydrator($issueTableGateway));
        $userWithIssuesArray = $userIssueTableGateway->fetchAll();

        return $userWithIssuesArray;
    }
}
