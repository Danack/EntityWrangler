<?php


$autoloader = require(__DIR__.'/../vendor/autoload.php');

use Auryn\Injector;
use EntityWranglerTest\Model\EmailAddress;
use EntityWranglerTest\Model\IssuePriority;
use EntityWranglerTest\Model\User;

/** @var $userDan User */
$userDan = null;

/** @var $userGordon User */
$userGordon = null;

/** @var $userGordon EmailAddress */
$emailAddress = null;


/**
 * @return \Auryn\Injector
 * @throws \Auryn\ConfigException
 */
function createTestInjector($mocks = array(), $shares = array())
{
    $injector = new \Auryn\Injector();

    // Read application config params
    $injectionParams = require __DIR__."/testInjectionParams.php";
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



function createData(Injector $injector)
{
    global $userDan;
    global $userGordon;
    
    $query = $injector->make('EntityWranglerTest\Magic\MoreMagic');

    $userDan = User::create('Dan', 'dman');
    $magicQuery = clone $query;
    $magicQuery->saveUser($userDan);

    $emailAddress = EmailAddress::create('Danack@example.com', $userDan->getUserId());
    $magicQuery = clone $query;
    $magicQuery->saveEmailAddress($emailAddress);

    $userGordon = User::create('Gordon', 'Smith');
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

    $treeDataSet = [
        [1, 1, "Fran What’s the cause of this bug?"],
        [2, 1, "Ollie I think it’s a null pointer."],
        [3, 2, "Fran No, I checked for that."],
        [4, 1, "Kukla We need to check for invalid input."],
        [5, 4, "Ollie Yes, that’s a bug."],
        [6, 4, "Fran Yes, please add a check for invalid input."],
        [7, 6, "Kukla That fixed it."],
    ];
//    foreach ($this->treeDataSet as $dataSet) {
//        $values = array();
//        $values['parent'] = $dataSet[1];
//        $values['text'] = $dataSet[2];
//        $sqlQuery->insertIntoMappedTable($table, $values);
//    }
}