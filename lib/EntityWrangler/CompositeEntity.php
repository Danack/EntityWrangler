<?php

namespace EntityWrangler;

use\EntityWrangler\CompositeEntityElement;

class CompositeEntity
{
    const TYPE_SINGLE = 'single';
    const TYPE_ARRAY = 'array';
    
    private $name;
    
    /** @var  CompositeEntityElement[] */
    private $elements;
    
    public function __construct($name, $elements)
    {
        $this->name = $name;
        $compositeElements = [];
        foreach ($elements as $key => $value) {
            $compositeElements[] = new CompositeEntityElement($key, $value);
        }
        $this->elements = $compositeElements;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return CompositeEntityElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }
}
