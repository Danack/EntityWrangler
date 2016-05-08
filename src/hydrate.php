<?php


use Zend\Stdlib\Hydrator;
$hydrator = new Hydrator\ArraySerializable();

$object = new ArrayObject(array());
$someData = ['foo' => 'bar'];
$hydrator->hydrate($someData, $object);

// or, if the object has data we want as an array:
$data = $hydrator->extract($object);