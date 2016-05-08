<?php


use BetterReflection\Reflector\ClassReflector;
use BetterReflection\SourceLocator\Type\ComposerSourceLocator;
use EntityWranglerTest\ModelComposite\UserWithIssuesWithComments;

$classLoader = require __DIR__."/../vendor/autoload.php";

$reflector = new ClassReflector(new ComposerSourceLocator($classLoader));
$reflectionClass = $reflector->reflect(UserWithIssuesWithComments::class);

foreach ($reflectionClass->getProperties() as $property) {
    echo "Name: ".$property->getName()."\n";
    foreach ($property->getDocBlockTypes() as $docBlockType) {
        echo "Type is: ". $docBlockType->__toString()."\n";
    }
}