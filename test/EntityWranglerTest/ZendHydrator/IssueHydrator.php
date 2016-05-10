<?php

namespace EntityWranglerTest\ZendHydrator;

use Zend\Hydrator\HydratorInterface;
use EntityWranglerTest\Model\Issue;
use EntityWranglerTest\Hydrator\HydratorException;

//class IssueHydrator implements Hydrator
//{
//    public function hydrate(array $data, HydratorRegistry $hydratorRegistry, $aliasPrefix)
//    {
//        $issue = new Issue();
//        $issue->issueId = $hydratorRegistry->extractValue($data, $aliasPrefix.'issue_id');
//        $issue->description = $hydratorRegistry->extractValue($data, $aliasPrefix.'description');
//        $issue->text = $hydratorRegistry->extractValue($data, $aliasPrefix.'text');
//        //$issue->priority = $hydratorRegistry->extractValue($data, $aliasPrefix.'priority');
//
//        return $issue;
//    }
//}



class IssueHydrator implements HydratorInterface
{
    public function hydrate(array $data, $issue)
    {
        if (!$issue instanceof Issue) {
            // Nothing to do.
            return $issue;
        }

        $issue->issueId = extractValue($data, 'issue_id');
        $issue->description = extractValue($data, 'description');
        $issue->text = extractValue($data, 'text');

        return $issue;
    }

    public function extract($object)
    {
        throw new \Exception("not implemented.");
    }
}
