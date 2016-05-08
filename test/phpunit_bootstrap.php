<?php


$autoloader = require(__DIR__.'/../vendor/autoload.php');




// Contains helper functions for the 'framework'.
//require_once __DIR__."/../src/appFunctions.php";

//require_once __DIR__."/mockFunctions.php";
//require_once __DIR__."/../src/appFunctions.php";
//require_once __DIR__."/../lib/Tier/tierFunctions.php";
//\Intahwebz\Functions::load();
//require_once "../../clavis.php";

//$autoloader->add('BlogTest', [__DIR__]);
//$autoloader->add('BlogMock', [__DIR__]);
//$autoloader->add('BlogStub', [__DIR__]);
//$autoloader->add(
//    "Jig\\PHPCompiledTemplate",
//    [realpath(realpath('./').'/tmp/generatedTemplates/')]
//);
//$autoloader->add(
//    "Jig\\PHPCompiledTemplate",
//    [realpath(realpath('./').'/test/compile/')]
//);


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


