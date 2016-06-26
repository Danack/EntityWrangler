<?php

use Auryn\Injector;
use EntityWrangler\MagicGenerator\MagicCompositeModelGenerator;
use EntityWrangler\MagicGenerator\MagicModelGenerator;
use EntityWrangler\CompositeEntity;
use EntityWrangler\CompositeElement;

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
    
    /** @var $table \EntityWrangler\EntityTableDefinition */
    $table = $tableName::createFromDefinition(
        $descriptionObject
    );

    $queryGenerator->addEntity($table);
}

$queryGenerator->generate();

$compositeEntities = [
    new CompositeEntity('UserWithIssues', [
        new CompositeElement('User', 'User', CompositeEntity::TYPE_SINGLE),
        new CompositeElement('Issue', 'Issues', CompositeEntity::TYPE_ARRAY),
    ]),
    new CompositeEntity('IssueWithComments', [
        new CompositeElement('Issue', 'Issue', CompositeEntity::TYPE_SINGLE),
        new CompositeElement('IssueComment', 'IssueComments', CompositeEntity::TYPE_ARRAY),
    ]),
    new CompositeEntity('UserWithIssuesWithComments', [
        new CompositeElement('User', 'User', CompositeEntity::TYPE_SINGLE),
        new CompositeElement('IssueWithComments', 'IssueWithComments', CompositeEntity::TYPE_ARRAY)
    ]),
    new CompositeEntity('UserWithEmailAddress', [
        new CompositeElement('User', 'User', CompositeEntity::TYPE_SINGLE),
        new CompositeElement('EmailAddress', 'EmailAddress', CompositeEntity::TYPE_SINGLE)
    ]),
    new CompositeEntity('UserWithEmailAddresses', [
        new CompositeElement('User', 'User', CompositeEntity::TYPE_SINGLE),
        new CompositeElement('EmailAddress', 'EmailAddresses', CompositeEntity::TYPE_ARRAY)
    ]),
];


$compositeModelGenerator = new MagicCompositeModelGenerator($savePath, $compositeEntities);
$compositeModelGenerator->generate();
