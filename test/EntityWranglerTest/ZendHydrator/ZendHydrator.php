<?php

namespace EntityWranglerTest\ZendHydrator;

use Zend\Hydrator\HydratorPluginManager;
use Zend\Hydrator\ObjectProperty;

class ZendHydrator
{
    public function __construct()
    {


        $brandTable = ProductTable::fromResultSet($result, 'b_');
        $productTable = ProductTable::fromResultSet($result, 'p_');

        $hydrator = new AggregateHydrator();
        $hydrator->add(new BrandHydrator($productTable));
        $hydrator->add(new ProductHydrator());

        $resultSet = new HydratingResultSet($hydrator, $rowObjectPrototype);
    }

}
