<?php


namespace EntityWrangler;

class CompositeElement
{
    private $name;
    private $type;
    
    public function __construct($name, $propertyName, $type)
    {
        $this->name = $name;
        $this->propertyName = $propertyName;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
}
