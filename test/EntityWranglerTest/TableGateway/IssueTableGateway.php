<?php

namespace EntityWranglerTest\TableGateway;

use EntityWranglerTest\Model\Issue;
use Zend\Hydrator\Aggregate\AggregateHydrator;

class IssueTableGateway
{
    private $data;
    private $prefix;

    /** @var  AggregateHydrator */
    private $aggregateHydrator;
    
    public static function fromResultSet(
        AggregateHydrator $aggregateHydrator,
        array $data,
        $prefix
    ) {
        $instance = new self();
        $instance->aggregateHydrator = $aggregateHydrator;
        $instance->data = $data;
        $instance->prefix = $prefix;

        return $instance;
    }

    public function findAllByUserId(array $filteredRows)
    {
        $issues = [];
        foreach ($filteredRows as $content) {
            $values = getPrefixedData($content, $this->prefix);
            $issue = new Issue();
            $issues[] = $this->aggregateHydrator->hydrate($values, $issue);
        }

        return $issues;
    }

    public function fetchAll()
    {
        $issues = [];
        $user = new \EntityWranglerTest\Model\Issue();
        foreach ($this->data as $content) {
            $values = getPrefixedData($content, $this->prefix);
            $issues[] = $this->aggregateHydrator->hydrate($values, $user);
        }

        return $issues;
    }
}
