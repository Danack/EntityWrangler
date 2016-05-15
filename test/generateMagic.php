<?php

use Auryn\Injector;
use EntityWrangler\MagicGenerator\MagicModelGenerator;

$autoloader = require(__DIR__.'/../vendor/autoload.php');

// Read application config params
$injectionParams = require __DIR__."/./testInjectionParams.php";

$injector = new Injector();

$injectionParams->addToInjector($injector);
$injector->share($injector);



$descriptions = [
    'EntityWranglerTest\EntityDefinition\EmailAddressDefinition',
    'EntityWranglerTest\EntityDefinition\IssueDefinition',
    'EntityWranglerTest\EntityDefinition\IssueCommentDefinition',
    'EntityWranglerTest\EntityDefinition\IssuePriorityDefinition',
    'EntityWranglerTest\EntityDefinition\UserDefinition',
];


$tableGenerator = $injector->make('EntityWrangler\MagicGenerator\MagicTableGenerator');
foreach ($descriptions as $description) {
    $descriptionObject = new $description();
    $tableGenerator->generate($descriptionObject);
}

$savePath = $injector->make('EntityWrangler\SavePath');

foreach ($descriptions as $description) {
    $descriptionObject = new $description();
    $modelGenerator = new MagicModelGenerator($savePath, $descriptionObject);
    $modelGenerator->generate();
}


$queryGenerator = $injector->make('EntityWrangler\MagicGenerator\MagicQueryGenerator');
foreach ($descriptions as $description) {
    $descriptionObject = new $description();
    /** @var  $descriptionObject \EntityWrangler\EntityDefinition */
    $tableName = sprintf(
        'EntityWranglerTest\Table\\%sTable',
        $descriptionObject->getTableInfo()->tableName
    );
    
    /** @var $table \EntityWrangler\EntityTable */
    $table = $tableName::createFromDefinition(
        $descriptionObject
    );

    $queryGenerator->addEntity($table);
}

$queryGenerator->generate();