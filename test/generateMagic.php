<?php

use Auryn\Injector;

$autoloader = require(__DIR__.'/../vendor/autoload.php');

// Read application config params
$injectionParams = require __DIR__."/./testInjectionParams.php";

$injector = new Injector();

$injectionParams->addToInjector($injector);
$injector->share($injector);

$descriptions = [
    'EntityWranglerTest\EntityDescription\EmailAddress',
    'EntityWranglerTest\EntityDescription\Issue',
    'EntityWranglerTest\EntityDescription\IssueComment',
    'EntityWranglerTest\EntityDescription\IssuePriority',
    'EntityWranglerTest\EntityDescription\User',
];


$tableGenerator = $injector->make('EntityWrangler\MagicGenerator\MagicTableGenerator');
foreach ($descriptions as $description) {
    $descriptionObject = new $description();
    $tableGenerator->generate($descriptionObject);
}

$queryGenerator = $injector->make('EntityWrangler\MagicGenerator\MagicQueryGenerator');
foreach ($descriptions as $description) {
    $descriptionObject = new $description();
    $tableName = 'EntityWranglerTest\Table\\'.$descriptionObject->getName().'Table';
    
    $table = $tableName::createFromDefinition(
        $descriptionObject
    );

    $queryGenerator->addEntity($table);
}

$queryGenerator->generate();