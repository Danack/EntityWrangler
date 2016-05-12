<?php

namespace EntityWranglerTest\TableGateway;

use EntityWranglerTest\Model\Issue;
//use Zend\Hydrator\Aggregate\AggregateHydrator;
use EntityWranglerTest\EntityFactory\AllKnownEntityFactory;

class IssueTableGateway
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
        $issues = [];
        foreach ($filteredRows as $content) {
            $values = getPrefixedData($content, $this->prefix);
            $issues[] = $this->allKnownEntityFactory->create(
                $values, 
                Issue::class
            );
        }

        return $issues;
    }

    public function fetchAll()
    {
        $issues = [];
        
        foreach ($this->data as $content) {
            $values = getPrefixedData($content, $this->prefix);
            $issues[] = $this->allKnownEntityFactory->create(
                $values, 
                Issue::class
            );
        }

        return $issues;
    }
}
