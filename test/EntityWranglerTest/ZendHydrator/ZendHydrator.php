<?php

namespace EntityWranglerTest\ZendHydrator;

use Zend\Hydrator\HydratorPluginManager;
use Zend\Hydrator\ObjectProperty;

class ZendHydrator
{
    public function __construct()
    {
        // Instantiate each hydrator you wish to delegate to
         $albumHydrator = new ObjectProperty;
         $artistHydrator = new ObjectProperty;
        
         // Map the entity class name to the hydrator using the HydratorPluginManager
         // In this case we have two entity classes, "Album" and "Artist"
         $hydrators = new HydratorPluginManager;
//         $hydrators->setService('Album', $albumHydrator);
//         $hydrators->setService('Artist', $artistHydrator);
//        
//         // Create the DelegatingHydrator and tell it to use our configured hydrator locator
//         $delegating = new DelegatingHydrator($hydrators);
        
         // Now we can use $delegating to hydrate or extract any supported object
//         $array = $delegating->extract(new Artist);
//         $artist = $delegating->hydrate($data, new Artist);
    }

}
