<?php

namespace EntityWranglerTest;


use EntityWrangler\SafeAccess;
use EntityWrangler\EntityWranglerException;

class CoverageTest extends \PHPUnit_Framework_TestCase
{
    use SafeAccess;

    public function testNoPropertyRead() {

        $this->setExpectedException(EntityWranglerException::class);
        echo $this->doesNotExist;
    }

    public function testNoPropertyWrite() {

        $this->setExpectedException(EntityWranglerException::class);
        $this->doesNotExist = 'foo';
    }

    public function testNoPropertyFuncCall() {

        $this->setExpectedException(EntityWranglerException::class);
        $this->doesNotExist();
    }
}


