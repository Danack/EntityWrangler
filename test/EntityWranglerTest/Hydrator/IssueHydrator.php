<?php

namespace EntityWranglerTest\Hydrator;

use EntityWranglerTest\Model\Issue;

class IssueHydrator implements Hydrator
{
    public function hydrate(array $data, HydratorRegistry $hydratorRegistry, $aliasPrefix)
    {
        $issue = new Issue();
        $issue->issueId = $hydratorRegistry->extractValue($data, $aliasPrefix.'issue_id');
        $issue->description = $hydratorRegistry->extractValue($data, $aliasPrefix.'description');
        $issue->text = $hydratorRegistry->extractValue($data, $aliasPrefix.'text');
        //$issue->priority = $hydratorRegistry->extractValue($data, $aliasPrefix.'priority');

        return $issue;
    }
}
