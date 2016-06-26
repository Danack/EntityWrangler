<?php

namespace EntityWranglerTest;

use EntityWranglerTest\Model\User;
use EntityWranglerTest\EntityDefinition\EmailAddressDefinition;
use EntityWranglerTest\EntityDefinition\IssueCommentDefinition;
use EntityWranglerTest\EntityDefinition\IssueDefinition;
use EntityWranglerTest\EntityDefinition\IssuePriorityDefinition;
use EntityWranglerTest\EntityDefinition\UserDefinition;
use EntityWrangler\EntityDefinitionList\ArrayEntityDefinitionList;
use Doctrine\DBAL\DriverManager;


class BasicTest extends \PHPUnit_Framework_TestCase 
{
    /** @var \Auryn\Injector */
    private $injector;
    
    /** @var \EntityWranglerTest\Magic\MoreMagic */
    private $query;
    
    public function setup()
    {
        $entityDefinitionList = new ArrayEntityDefinitionList([
            IssuePriorityDefinition::class,
            IssueCommentDefinition::class,
            IssueDefinition::class,
            UserDefinition::class,
            EmailAddressDefinition::class,
        ]);
        
        
        $this->injector = createTestInjector();
        delegateTables($this->injector);
        $conn = DriverManager::getConnection(['pdo' => new \PDO('sqlite:testing.sqlite')]);
        migrateDatabase($conn, $entityDefinitionList);
        createData($this->injector);
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

    function testSelectWhereSimple()
    {
        $userEntity = $this->query->userTable()->whereColumn('firstName', 'Dan');
        $emailAddressEntity = $this->query->emailAddressTable();
        $userWithEmailAddressesList = $this->query->fetchAsUserWithEmailAddress();
        $object = $userWithEmailAddressesList[0];
        $this->assertInstanceOf('EntityWranglerTest\Model\User', $object->user);
        $this->assertInternalType('array', $object->emailAddresses);
        $emailAddress = $object->emailAddresses[0];

        $this->assertInstanceOf('EntityWranglerTest\Model\EmailAddress', $emailAddress);
        $this->assertEquals("Dan", $object->user->firstName);
        $this->assertEquals("dman", $object->user->lastName); 
        $this->assertEquals("Danack@example.com", $emailAddress->address);
    }


    public function testLimit()
    {
        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        //$userTable = EntityTable::createFromDefinition(new UserDefinition());
        $query->userTable()->limit(1);
        $contentArray = $query->fetch();
        $this->assertCount(1, $contentArray);
    }

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
        global $userDan, $userGordon;

        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        $query->userTable()->order(User::COLUMN_FIRST_NAME, 'DESC');

        $contentArray = $query->fetch();
        $this->assertEquals($userGordon->getUserId(), $contentArray[0]['User_user_id']);
        $this->assertEquals($userDan->getUserId(), $contentArray[1]['User_user_id']);
    }


    function testOrderByEntity()
    {
        global $userDan, $userGordon;

        $query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
        $userEntity = $query->userTable();
        $query->order($userEntity, 'first_name', 'DESC');

        $contentArray = $query->fetch();
        $this->assertEquals($userGordon->getUserId(), $contentArray[0]['User_user_id']);
        $this->assertEquals($userDan->getUserId(), $contentArray[1]['User_user_id']);
    }

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

    /**
     * @group magic
     */
    function testSelectWhereIn()
    {
        global $userDan;

        $this->query->userTable()->whereColumnIn('firstName'/* User::COLUMN_FIRST_NAME */, ['aaaa', 'bbbb', $userDan->getFirstName()]);
        $contentArray = $this->query->fetch();
        $this->assertInternalType('array', $contentArray);
        $this->assertEquals($userDan->getFirstName(), $contentArray[0]['User_first_name']);
    }
}
