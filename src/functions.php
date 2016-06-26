<?php

use EntityWrangler\EntityDefinition;
use EntityWranglerTest\Hydrator\HydratorException;
use EntityWrangler\SchemaBuilder;

function snakify($word)
{
    return preg_replace(
        '/(^|[a-z])([A-Z])/e',
        'strtolower(strlen("\\1") ? "\\1_\\2" : "\\2")',
        $word
    );
}

function camelize($word)
{
    return preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $word);
}

function getClassName($namespaceClass)
{
    $lastSlashPosition = mb_strrpos($namespaceClass, '\\');

    if ($lastSlashPosition !== false) {
        return mb_substr($namespaceClass, $lastSlashPosition + 1);
    }

    return $namespaceClass;
}

function getNamespace($namespaceClass)
{
    if (is_object($namespaceClass)) {
        $namespaceClass = get_class($namespaceClass);
    }

    $lastSlashPosition = mb_strrpos($namespaceClass, '\\');

    if ($lastSlashPosition !== false) {
        return mb_substr($namespaceClass, 0, $lastSlashPosition);
    }

    return "\\";
}

function getPrefixedData($data, $prefix)
{
    $values = [];

    foreach ($data as $key => $value) {
        if (strpos($key, $prefix) === 0) {
            $values[substr($key, strlen($prefix) + 1)] = $value;
        }
    }

    return $values;
}


function extractValue(array $data, $keyName)
{
    if (array_key_exists($keyName, $data) === true) {
        return $data[$keyName];
    }

    throw new HydratorException("Missing key '$keyName' in data ".var_export($data, true));
}


function formatSQL($sql)
{
    $searchReplace = [
        "," => ",\n",
        "FROM" => "\nFROM",
        "LEFT JOIN" => "\nLEFT JOIN"
    ];

    return str_replace(array_keys($searchReplace), $searchReplace, $sql);
}


/**
 * @param EntityDefinition $entityDefinition
 * @return \EntityWrangler\Definition\EntityField[]
 */
function getAllEntityFields(EntityDefinition $entityDefinition, $includeIdentity)
{    
    $properties = $entityDefinition->getProperties();
    $relations = $entityDefinition->getRelations();

    $fields = array_merge($properties, $relations);
    
    if ($includeIdentity) {
        $fields = array_merge([$entityDefinition->getIdentity()], $fields);
    }
    
    return $fields;
}


function migrateDatabase(
    \Doctrine\DBAL\Connection $conn,
    \EntityWrangler\EntityDefinitionList $entityDefinitionList)
{
    $schemaManager = $conn->getSchemaManager();
    $fromSchema = $schemaManager->createSchema();
    $toSchema = new \Doctrine\DBAL\Schema\Schema();
    $schemaGenerator = new SchemaBuilder($toSchema);

    foreach ($entityDefinitionList->getEntityDefinitions() as $knownEntity) {
        $schemaGenerator->addEntityDefinition(new $knownEntity());
    }
    $generatedSchema = $schemaGenerator->build();
    $sqlArray = $fromSchema->getMigrateToSql($toSchema, $conn->getDatabasePlatform());
    foreach ($sqlArray as $sql) {
        $conn->exec($sql);
    }

    foreach ($generatedSchema->getTableDefinitions() as $tableDefinition) {
        $sql = "delete from ".$tableDefinition->getName();
        $conn->exec($sql);
    }
}