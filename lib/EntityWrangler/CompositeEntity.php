<?php

namespace EntityWrangler;

use\EntityWrangler\CompositeElement;

class CompositeEntity
{
    const TYPE_SINGLE = 'single';
    const TYPE_ARRAY = 'array';
    
    private $name;
    
    /** @var  CompositeElement[] */
    private $elements;
    
    public function __construct($name, $compositeElements)
    {
        $this->name = $name;
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
     * @return CompositeElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }
}
