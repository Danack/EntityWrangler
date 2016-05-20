<?php

namespace EntityWranglerTest\TableGateway;

use EntityWranglerTest\Model\EmailAddress;
use EntityWranglerTest\EntityFactory\AllKnownEntityFactory;

class EmailAddressTableGateway

{
    private $data;
    private $prefix;

    /** @var  AllKnownEntityFactory */
    private $allKnownEntityFactory;
    
    public static function fromResultSet(
        AllKnownEntityFactory $allKnownEntityFactory,
        array $data,
        $prefix
    ) {
        $instance = new self();
        $instance->allKnownEntityFactory = $allKnownEntityFactory;
        $instance->data = $data;
        $instance->prefix = $prefix;

        return $instance;
    }

    public function findAllByUserId(array $filteredRows)
    {
        $emailAddresses = [];
        foreach ($filteredRows as $content) {
            $values = getPrefixedData($content, $this->prefix);
            $emailAddresses[] = $this->allKnownEntityFactory->create(
                $values, 
                EmailAddress::class
            );
        }

        return $emailAddresses;
    }

    public function fetchAll()
    {
        $issues = [];
        
        foreach ($this->data as $content) {
            $values = getPrefixedData($content, $this->prefix);
            $issues[] = $this->allKnownEntityFactory->create(
                $values, 
                EmailAddress::class
            );
        }

        return $issues;
    }
}
