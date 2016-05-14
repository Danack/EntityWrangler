<?php

namespace EntityWranglerTest;

use EntityWrangler\EntityTable;
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
use EntityWranglerTest\Table\UserTable;
use EntityWranglerTest\Table\IssueTable;


//use EntityWranglerTest\TableGateway\UserTableGateway;
use Zend\Hydrator\Aggregate\AggregateHydrator;

use EntityWranglerTest\TableGateway\IssueTableGateway;
use EntityWranglerTest\TableGateway\IssueCommentTableGateway;
use EntityWranglerTest\TableGateway\UserTableGateway;
use EntityWranglerTest\TableGateway\UserIssueTableGateway;


use EntityWranglerTest\ZendHydrator\IssueHydrator;
use EntityWranglerTest\ZendHydrator\UserHydrator;
use EntityWranglerTest\ZendHydrator\UserIssueHydrator;





class BasicTest extends \PHPUnit_Framework_TestCase 
{
    /** @var \Auryn\Injector */
    private $injector;
    
    public function setup()
    {
        $this->injector = createTestInjector();
        $this->buildDB();
    }

    public function delegateTables()
    {
        $userFn = function() {
            $userDef = new User();
            return UserTable::createFromDefinition($userDef);
        };

        $this->injector->delegate('EntityWranglerTest\Table\UserTable', $userFn);

        $issueFn = function() {
            $issueDef = new Issue();
            return IssueTable::createFromDefinition($issueDef);
        };

        $this->injector->delegate('EntityWranglerTest\Table\IssueTable', $issueFn);
    }
    
    public static function buildDB()
    {
        $conn = DriverManager::getConnection(['pdo' => new \PDO('sqlite:testing.sqlite')]);
        $conn->exec("DROP TABLE IF EXISTS User;");
        $conn->exec(
          "CREATE TABLE User (
            user_id INTEGER PRIMARY KEY,
            first_name VARCHAR NOT NULL ,
            last_name VARCHAR NOT NULL
            );"
        );

        //$userId = $conn->lastInsertId();

        $conn->exec("INSERT INTO User ('first_name', 'last_name') VALUES ('Dan','dman');");
        $danUserId = $conn->lastInsertId();
        
        $conn->exec("INSERT INTO User ('first_name', 'last_name') VALUES ('Gordon','Smith');");
        $gordonUserId = $conn->lastInsertId();
        
        $conn->exec("DROP TABLE IF EXISTS EmailAddress;");
        $conn->exec(
          "CREATE TABLE EmailAddress (
            email_address_id INTEGER PRIMARY KEY,
            user_id INTEGER NOT NULL,
            address VARCHAR NOT NULL
          );"
        );

        $conn->exec("INSERT INTO EmailAddress ('user_id', 'address') VALUES ('$danUserId','test@example.com');");
        $emailAddressId = $conn->lastInsertId();

        $conn->exec("DROP TABLE IF EXISTS IssuePriority;");
        $conn->exec(
          "CREATE TABLE IssuePriority (
            issue_priority_id INTEGER PRIMARY KEY,
            description VARCHAR NOT NULL
          );"
        );

        $conn->exec("INSERT INTO IssuePriority (description) VALUES ('low');");
        $lowPriorityId = $conn->lastInsertId();

        $conn->exec("INSERT INTO IssuePriority (description) VALUES ('medium');");
        $mediumPriorityId = $conn->lastInsertId();
        
        $conn->exec("INSERT INTO IssuePriority (description) VALUES ('high');");
        $highPriorityId = $conn->lastInsertId();

        $conn->exec("DROP TABLE IF EXISTS Issue;");
        $conn->exec(
          "CREATE TABLE Issue (
            issue_id INTEGER PRIMARY KEY,
            description VARCHAR NOT NULL,
            text VARCHAR NOT NULL,
            user_id INTEGER NOT NULL,
            issue_priority_id INTEGER NOT NULL
          );"
        );

        $issueId = $conn->exec("INSERT INTO Issue (description, text, user_id, issue_priority_id) VALUES ('issue the first', 'checking this works', $danUserId, $lowPriorityId);");
        $conn->exec("INSERT INTO Issue (description, text, user_id, issue_priority_id) VALUES ('second issue', 'Lorem ipsum description', $danUserId, $mediumPriorityId);");
        $conn->exec("INSERT INTO Issue (description, text, user_id, issue_priority_id) VALUES ('third issue', 'Lorem ipsum description adiahodiahoidhoadoiahdoiasdh', $gordonUserId, $lowPriorityId);");


        $conn->exec("DROP TABLE IF EXISTS IssueComments;");        
        $conn->exec("DROP TABLE IF EXISTS IssueComment;");
        $conn->exec(
          "CREATE TABLE IssueComment (
            issue_comment_id INTEGER PRIMARY KEY,
            issue_id INTEGER NOT NULL,
            text VARCHAR NOT NULL,
            user_id INTEGER COMMENT 'The user that made this comment' NOT NULL
          );"
        );
        
        
        $dataForIssueComments = [
            [$issueId, "Help please", $danUserId], 
            [$issueId, "what is the nature of the medical emergency", $gordonUserId],
        ];
        
        foreach ($dataForIssueComments as $dataForIssueComment) {

            $statement = $conn->prepare(
                "INSERT INTO IssueComment (issue_id, text, user_id) VALUES (:issueId, :text, :user_id);"
            );
            
            $statement->bindParam('issueId', $dataForIssueComment[0], \PDO::PARAM_INT);
            $statement->bindParam('text', $dataForIssueComment[1], \PDO::PARAM_STR);
            $statement->bindParam('user_id', $dataForIssueComment[2], \PDO::PARAM_INT);

            $statement->execute();
        }

//        echo "Users ***************\n";
//        $statement = $conn->query("select * from User;");
//        var_dump($statement->fetchAll(\PDO::FETCH_ASSOC));
//
//        echo "Issue ***************\n";
//        $statement = $conn->query("select * from Issue;");
//        var_dump($statement->fetchAll(\PDO::FETCH_ASSOC));
//
//
//        $debug = "SELECT User.user_id as User_user_id,
// User.first_name as User_first_name,
// User.last_name as User_last_name,
// Issue.issue_id as Issue_issue_id,
// Issue.description as Issue_description,
// Issue.text as Issue_text
//FROM User User
//JOIN Issue Issue
//ON User.user_id = Issue.issue_id
//WHERE user.first_name like 'Dan' order by Issue_issue_id desc";
//
//        echo "Join ***************\n";
//        $statement = $conn->query($debug);
//        var_dump($statement->fetchAll(\PDO::FETCH_ASSOC));
//
//        exit(0);
    }

    
    function testSimplest()
    {   
        $query = $this->injector->make('EntityWrangler\Query\Query');
        $userTable = EntityTable::createFromDefinition(new User());        
        $query->table($userTable);//->whereColumn('mockNoteID', 1);
        $contentArray = $query->fetch();
    }
    
    function testSelectWhereEmpty()
    {   
        $query = $this->injector->make('EntityWrangler\Query\Query');
        //$table = $this->injector->make('Entity\User');

        $userTable = EntityTable::createFromDefinition(new User());        
        $query->table($userTable)->whereColumn('firstName', 'John');
        $contentArray = $query->fetch();
        $this->assertInternalType('array', $contentArray);
        $this->assertEmpty($contentArray, "John shouldn't have been found.");
    }

    /**
     * @group broken
     */
    function testSelectWhereSimple()
    {   
        $query = $this->injector->make('EntityWrangler\Query\Query');
        
        $userTable = EntityTable::createFromDefinition(new User());     
        $emailAddressTable = EntityTable::createFromDefinition(new EmailAddress());

        $userEntity = $query->table($userTable)->whereColumn('firstName', 'Dan');
        $emailAddressEntity = $query->table($emailAddressTable);
        $contentArray = $query->fetch();
        $hydratorRegistry = new HydratorRegistry();        
        $hydrator = new UserWithEmailHydrator(
            $userEntity,
            $emailAddressEntity
        );

        $object = $hydrator->hydrate($contentArray[0], $hydratorRegistry, '');
    
        $this->assertInstanceOf('EntityWranglerTest\Model\User', $object->user);
        $this->assertInstanceOf('EntityWranglerTest\Model\EmailAddress', $object->emailAddress);
        $this->assertEquals(1, $object->user->userId);
        $this->assertEquals("Dan", $object->user->firstName);
        $this->assertEquals("dman", $object->user->lastName); 
        $this->assertEquals(1, $object->emailAddress->emailAddressId);
        $this->assertEquals("test@example.com", $object->emailAddress->address);
    }

    /**
     * @group testing
     */
    function testSelectWhereIssues()
    {   
        $query = $this->injector->make('EntityWrangler\Query\Query');
        
        $userTable = EntityTable::createFromDefinition(new User());
        $issueTable = EntityTable::createFromDefinition(new Issue());
        $issueCommentTable = EntityTable::createFromDefinition(new IssueComment());

        $emailAddressEntity = $query->table($issueTable);
        $issueCommentEntity = $query->table($issueCommentTable);
        $userEntity = $query->table($userTable);//->whereColumn('firstName', 'Dan');
        $contentArray = $query->fetch();

        $hydratorRegistry = new HydratorRegistry();
        $hydrator = new IssueWithCommentsAndUserHydrator(
            $userEntity,
            $emailAddressEntity,
            $issueCommentEntity
        );

        $objects = [];
        $object = $hydrator->hydrate($contentArray, $hydratorRegistry);
    }

    /**
     * @group testing
     */
    public function testLimit()
    {
        $query = $this->injector->make('EntityWrangler\Query\Query');
        $userTable = EntityTable::createFromDefinition(new User());
        $userEntity = $query->table($userTable);
        $query->limit(1);
        $contentArray = $query->fetch();

        $this->assertCount(1, $contentArray);
    }


        /**
     * @group testing
     */
    public function testOffset()
    {
        $query = $this->injector->make('EntityWrangler\Query\Query');
        $userTable = EntityTable::createFromDefinition(new User());
        $userEntity = $query->table($userTable);
        $query->offset(1);
        $contentArray = $query->fetch();

        $this->assertCount(1, $contentArray);
    }
    
    
    function testOrder()
    {
        $query = $this->injector->make('EntityWrangler\Query\Query');
        $userTable = EntityTable::createFromDefinition(new User());
        $userEntity = $query->table($userTable);
        $query->order($userEntity, 'user_id', 'DESC');

        $contentArray = $query->fetch();
        $this->assertEquals(2, $contentArray[0]['User_user_id']);
        $this->assertEquals(1, $contentArray[1]['User_user_id']);
    }


    /**
     * @group magic
     */
    function testMagic()
    {
        $this->delegateTables();
        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        $query->userTable()->whereFirstNameEquals('Dan');        
        $contentArray = $query->fetch();
    }
    
    /**
     * @group magic
     */
    function testMoreMagic()
    {
        //https://zf2.readthedocs.io/en/latest/modules/zend.stdlib.hydrator.aggregate.html
        $this->delegateTables();

        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        $query->userTable()->whereFirstNameEquals('Dan');
        $query->issueTable();
        $userWithIssuesArray = $query->getAllAsUserWithIssues();

        foreach ($userWithIssuesArray as $userWithIssues) {
            $this->assertInstanceOf(UserWithIssues::class, $userWithIssues);
            $this->assertEquals("Dan", $userWithIssues->user->firstName);
            $this->assertCount(2, $userWithIssues->issues);
        }
    }
    
    function testSave()
    {
        $this->delegateTables();
        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        $query->createUser();
    }
    
}
