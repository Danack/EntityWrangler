<?php

namespace EntityWranglerTest;

use EntityWrangler\EntityTable;
use EntityWranglerTest\EntityDefinition\EmailAddressDefinition;
use EntityWranglerTest\EntityDefinition\UserDefinition;
use EntityWranglerTest\EntityDefinition\IssueDefinition;
use EntityWranglerTest\EntityDefinition\IssueCommentDefinition;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use EntityWranglerTest\Hydrator\HydratorRegistry;
use EntityWranglerTest\Hydrator\UserWithEmailHydrator;
use EntityWranglerTest\Hydrator\UserWithIssuesHydrator;
use EntityWranglerTest\Hydrator\UserWithIssuesWithCommentsHydrator;
use EntityWranglerTest\ModelComposite\UserWithIssuesWithComments;
use EntityWranglerTest\Hydrator\IssueWithCommentsAndUserHydrator;

use EntityWranglerTest\ModelComposite\UserWithIssues;
use EntityWranglerTest\Table\UserTable;
use EntityWranglerTest\Table\IssueTable;


//use EntityWranglerTest\TableGateway\UserTableGateway;
use EntityWranglerTest\Model\User;
use EntityWrangler\EntityWranglerException; 



use Ramsey\Uuid\Uuid;





class BasicTest extends \PHPUnit_Framework_TestCase 
{
    /** @var \Auryn\Injector */
    private $injector;
    
    /** @var \EntityWranglerTest\Magic\MoreMagic */
    private $query;
    
    public function setup()
    {
        $this->injector = createTestInjector();
        delegateTables($this->injector);
        setupDatabase($this->injector);
        
        $this->query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
    }

    /**
     * @group magicsave
     */
    public function testSaveAndLoadUser()
    {
        $this->saveUser('Johnny', 'Niedermann');
        $this->loadUser('Johnny', 'Niedermann');
    }

    
    function saveUser($firstName, $lastName)
    {
        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        
        $user = User::create($firstName, $lastName);
        $query->saveUser($user);

        return $user;
    }
    
    public function loadUser($firstName, $expectedLastName)
    {
        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        try {
            $query->userTable()->whereColumn('firstName', $firstName);
            $result = $query->fetchAsUsers();
            $this->assertCount(1, $result);
            foreach ($result as $user) {
                $this->assertInstanceOf(User::class, $user);
                $this->assertEquals($expectedLastName, $user->getLastName());
                return $user;
            }
            $this->fail('This should never be reached');
            return null;
            
        }
        catch (EntityWranglerException $ewe) {
            echo "Message is: ".$ewe->getMessage();
            echo $query->getQueryBuilder()->getSQL();
            //exit(0);
        }
    }


    /**
     * @group magic
     */
    function testSelectWhereEmpty()
    {
        $this->query->userTable()->whereColumn('firstName', 'John');
        $contentArray = $this->query->fetch();
        $this->assertInternalType('array', $contentArray);
        $this->assertEmpty($contentArray, "John shouldn't have been found.");
    }

    /**
     * @group magic
     */
    function testSelectWhereSimple()
    {
        $userEntity = $this->query->userTable()->whereColumn('firstName', 'Dan');
        $emailAddressEntity = $this->query->emailAddressTable();

        $contentArray = $this->query->getAllAsUserWithEmailAddress();

        $object = $contentArray[0];

        $this->assertInstanceOf('EntityWranglerTest\Model\User', $object->user);
        $this->assertInstanceOf('EntityWranglerTest\Model\EmailAddress', $object->emailAddress);
        $this->assertEquals(1, $object->user->userId);
        $this->assertEquals("Dan", $object->user->firstName);
        $this->assertEquals("dman", $object->user->lastName); 
        $this->assertEquals(1, $object->emailAddress->emailAddressId);
        $this->assertEquals("test@example.com", $object->emailAddress->address);
    }

//    /**
//     * @group testing
//     */
//    function testSelectWhereIssues()
//    {   
//        $query = $this->injector->make('EntityWrangler\Query\Query');
//        
//        $userTable = EntityTable::createFromDefinition(new UserDefinition());
//        $issueTable = EntityTable::createFromDefinition(new IssueDefin());
//        $issueCommentTable = EntityTable::createFromDefinition(new IssueComment());
//
//        $emailAddressEntity = $query->table($issueTable);
//        $issueCommentEntity = $query->table($issueCommentTable);
//        $userEntity = $query->table($userTable);//->whereColumn('firstName', 'Dan');
//        $contentArray = $query->fetch();
//
//        $hydratorRegistry = new HydratorRegistry();
//        $hydrator = new IssueWithCommentsAndUserHydrator(
//            $userEntity,
//            $emailAddressEntity,
//            $issueCommentEntity
//        );
//
//        $objects = [];
//        $object = $hydrator->hydrate($contentArray, $hydratorRegistry);
//    }

    /**
     * @group testing
     */
    public function testLimit()
    {
        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        //$userTable = EntityTable::createFromDefinition(new UserDefinition());
        $userEntity = $query->userTable()->limit(1);
        //$query->limit(1);
        $contentArray = $query->fetch();

        $this->assertCount(1, $contentArray);
    }


        /**
     * @group testing
     */
    public function testOffset()
    {
        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        //$userTable = EntityTable::createFromDefinition(new UserDefinition());
        $userEntity = $query->userTable();
        $query->offset(1);
        $contentArray = $query->fetch();

        $this->assertCount(1, $contentArray);
    }
    
    
    function testOrder()
    {
        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        //$userTable = EntityTable::createFromDefinition(new UserDefinition());
        $userEntity = $query->userTable();
        $query->order($userEntity, 'user_id', 'DESC');

        $contentArray = $query->fetch();
        $this->assertEquals(2, $contentArray[0]['User_user_id']);
        $this->assertEquals(1, $contentArray[1]['User_user_id']);
    }


    
    /**
     * @group magic
     */
    function testMoreMagic()
    {
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
    
}
