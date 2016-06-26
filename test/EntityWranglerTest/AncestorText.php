<?php


namespace EntityWranglerTest;

class AncestorTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Auryn\Injector */
    private $injector;
    
    /** @var \EntityWranglerTest\Magic\MoreMagic */
    private $query;
    
    public function setup()
    {
        $this->injector = createTestInjector();
        delegateTables($this->injector);
        setupAncestorDatabase($this->injector);
        $this->query = $this->injector->make('EntityWranglerTest\Magic\MoreMagic');
    }

}
