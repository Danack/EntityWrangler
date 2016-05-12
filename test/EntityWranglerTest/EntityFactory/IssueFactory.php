<?php

namespace EntityWranglerTest\EntityFactory;

use EntityWranglerTest\Model\Issue;
use EntityWranglerTest\EntityFactory;

class IssueFactory implements EntityFactory
{
    public function create(array $data)
    {
        $issueId = extractValue($data, 'issue_id');
        $description = extractValue($data, 'description');
        $text = extractValue($data, 'text');
        $instance = new Issue($issueId, $description, $text);

        return $instance;
    }

}
