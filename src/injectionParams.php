<?php

use Tier\InjectionParams;


// These classes will only be created  by the injector once
$shares = [
    new \EntityWrangler\Generator\ClassPath(__DIR__."/../var/analysis"),
];
    

// Alias interfaces (or classes) to the actual types that should be used 
// where they are required. 
$aliases = [
];


// Delegate the creation of types to callables.
$delegates = [
    //\GithubService\GithubArtaxService\GithubService::class => 'createGithubService',
];

// If necessary, define some params that can be injected purely by name.
$params = [ ];

$defines = [
];

$prepares = [

];

$injectionParams = new InjectionParams(
    $shares,
    $aliases,
    $delegates,
    $params,
    $prepares,
    $defines
);

return $injectionParams;
