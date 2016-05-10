<?php

namespace EntityWranglerTest\ZendHydrator;

use EntityWranglerTest\TableGateway\IssueTableGateway;
use Zend\Hydrator\HydratorInterface;
use EntityWranglerTest\Model\UserWithIssues;
use EntityWranglerTest\Hydrator\HydratorException;

class UserIssueHydrator implements HydratorInterface
{
    private $issueTableGateway;

    public function __construct(IssueTableGateway $issueTableGateway)
    {
        $this->issueTableGateway = $issueTableGateway;
    }
    
    public function hydrate(array $data, $userWithIssues)
    {
        if (!$userWithIssues instanceof UserWithIssues) {
            // Nothing to do.
            return $userWithIssues;
        }

//        $userWithIssues->userId = extractValue($data, 'issue_id');
//        $userWithIssues->firstName = extractValue($data, 'description');
//        $userWithIssues->lastName = extractValue($data, 'text');

        $this->issueTableGateway->fetchAll();

        return $userWithIssues;
    }
    
    public function extract($object)
    {
        throw new \Exception("not implemented.");
    }
}
