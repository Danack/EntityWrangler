<?php


namespace EntityWranglerTest\Hydrator;

interface Hydrator
{
    public function hydrate(array $data, HydratorRegistry $hydratorRegistry, $aliasPrefix);
}
