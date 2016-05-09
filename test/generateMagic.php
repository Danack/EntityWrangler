<?php

$autoloader = require(__DIR__.'/../vendor/autoload.php');

// Read application config params
$injectionParams = require __DIR__."/./testInjectionParams.php";

$injector = new \Auryn\Injector();

$injectionParams->addToInjector($injector);
$injector->share($injector);


$queryGenerator = $injector->make('EntityWrangler\MagicGenerator\MagicQueryGenerator');

$tableGenerator = $injector->make('EntityWrangler\MagicGenerator\MagicTableGenerator');



$descriptions = [
    'EntityWranglerTest\EntityDescription\EmailAddress',
    'EntityWranglerTest\EntityDescription\Issue',
    'EntityWranglerTest\EntityDescription\IssueComment',
    'EntityWranglerTest\EntityDescription\IssuePriority',
    'EntityWranglerTest\EntityDescription\User',
];

foreach ($descriptions as $description) {
    $descriptionObject = new $description();
    $tableGenerator->generate($descriptionObject);
}

foreach ($descriptions as $description) {
    $descriptionObject = new $description();
    $tableName = 'EntityWranglerTest\Table\\'.$descriptionObject->getName().'Table';
    
    $table = $tableName::createFromDefinition(
        $descriptionObject
    );

    $queryGenerator->addEntity($table);

}


$queryGenerator->generate();