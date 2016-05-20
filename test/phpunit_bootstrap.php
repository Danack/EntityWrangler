<?php


$autoloader = require(__DIR__.'/../vendor/autoload.php');

use Auryn\Injector;
use EntityWranglerTest\EntityDefinition\EmailAddressDefinition;
use EntityWranglerTest\EntityDefinition\IssueCommentDefinition;
use EntityWranglerTest\EntityDefinition\IssueDefinition;
use EntityWranglerTest\EntityDefinition\IssuePriorityDefinition;
use EntityWranglerTest\EntityDefinition\UserDefinition;
use EntityWranglerTest\Model\EmailAddress;
use EntityWranglerTest\Table\UserTable;
use EntityWranglerTest\Table\IssuePriorityTable;
use EntityWranglerTest\Table\IssueTable;
use Doctrine\DBAL\DriverManager;
use EntityWrangler\EntityTable;
use EntityWrangler\Definition\EntityIdentity;
use EntityWranglerTest\Model\IssuePriority;
use EntityWranglerTest\Model\User;


/**
 * @return \Auryn\Injector
 * @throws \Auryn\ConfigException
 */
function createTestInjector($mocks = array(), $shares = array())
{
    $injector = new \Auryn\Injector();

    // Read application config params
    $injectionParams = require __DIR__."/./testInjectionParams.php";
    /** @var $injectionParams \Tier\InjectionParams */

    $injectionParams->mergeMocks($mocks);
    foreach ($mocks as $class => $implementation) {
        if (is_object($implementation) == true) {
            $injector->alias($class, get_class($implementation));
            $injector->share($implementation);
        }
        else {
            $injector->alias($class, $implementation);
        }
    }
    
    $injectionParams->addToInjector($injector);
    $injector->share($injector);
    
    return $injector;
}




function delegateTables(Injector $injector)
{
    $entities = [
        'EmailAddress',
        'Issue',
        'IssuePriority',
        'User'
    ];
    
    foreach ($entities as $entity) {
        
        $tableName = sprintf(
            'EntityWranglerTest\Table\%sTable',
            $entity
        );
        
        $fn = function() use ($entity, $tableName) {
            $definitionName = sprintf(
                'EntityWranglerTest\EntityDefinition\%sDefinition',
                $entity
            );

            $userDef = new $definitionName();
            return $tableName::createFromDefinition($userDef);
        };

        $injector->delegate($tableName, $fn);
    }
}

/** @var $userDan User */
$userDan = null;

/** @var $userGordon User */
$userGordon = null;

/** @var $userGordon EmailAddress */
$emailAddress = null;

function setupDatabase(Injector $injector)
{
    global $userDan;
    global $userGordon;

    $conn = DriverManager::getConnection(['pdo' => new \PDO('sqlite:testing.sqlite')]);

    $schemaManager = $conn->getSchemaManager();
    $fromSchema = $schemaManager->createSchema();
    $toSchema = new \Doctrine\DBAL\Schema\Schema();
    $knownEntities = [
        IssuePriorityDefinition::class,
        IssueCommentDefinition::class,
        IssueDefinition::class,
        UserDefinition::class,
        EmailAddressDefinition::class,
    ];

    $cleanupSQLArray = [];
    
    foreach ($knownEntities as $knownEntity) {
        $userTable = EntityTable::createFromDefinition(new $knownEntity());
        $table = $toSchema->createTable($userTable->getName());
        foreach ($userTable->getProperties() as $field) {
            $type = $field->type;
            if ($field->type == 'identity') {
                $type = 'string';
            }
            $table->addColumn($field->getDBName(), $type, ['length' => 255]);
        }

        foreach ($userTable->getRelations() as $relation) {
            $type = 'string';
            $options = ['length' => 255];
            if ($relation->entityIdentity->getType() == EntityIdentity::TYPE_PRIMARY) {
                $type = 'integer';
                $options = [];
            }
            $table->addColumn($relation->getDBName(), $type, $options);
        }

        $cleanupSQLArray[] = "delete from ".$userTable->getName();
        // TODO add
        // charset 
        // collation
        // $myForeign->addForeignKeyConstraint
    }

    $sqlArray = $fromSchema->getMigrateToSql($toSchema, $conn->getDatabasePlatform());
    // var_dump($sqlArray);
    foreach ($sqlArray as $sql) {
        $conn->exec($sql);
    }
    foreach ($cleanupSQLArray as $sql) {
        $conn->exec($sql);
    }

    $query = $injector->make('EntityWranglerTest\Magic\MoreMagic');
    
    $userDan = User::create('Dan','dman');
    $magicQuery = clone $query;
    $magicQuery->saveUser($userDan);
    
    $emailAddress = EmailAddress::create('Danack@example.com', $userDan->getUserId());
    $magicQuery = clone $query;
    $magicQuery->saveEmailAddress($emailAddress);
    
    $userGordon = User::create('Gordon','Smith');
    $magicQuery = clone $query;
    $magicQuery->saveUser($userGordon);
    
    $priorities = [
        'low',
        'medium',
        'high'
    ];
    
    foreach ($priorities as $priority) {
        $issuePriority = IssuePriority::create($priority);
        $magicQuery = clone $query;
        $magicQuery->saveIssuePriority($issuePriority);
    }
}
