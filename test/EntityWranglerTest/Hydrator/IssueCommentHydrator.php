<?php

namespace EntityWranglerTest\Hydrator;

use EntityWranglerTest\Model\IssueComment;

class IssueCommentHydrator implements Hydrator
{
    public function hydrate(array $data, HydratorRegistry $hydratorRegistry, $aliasPrefix)
    {
        $issue = new IssueComment();
        $issue->issueCommentId = $hydratorRegistry->extractValue($data, $aliasPrefix.'issue_comment_id');
        $issue->issueId = $hydratorRegistry->extractValue($data, $aliasPrefix.'issue_id');
        $issue->text = $hydratorRegistry->extractValue($data, $aliasPrefix.'text');
        $issue->userId = $hydratorRegistry->extractValue($data, $aliasPrefix.'user_id');

        return $issue;
    }
}
