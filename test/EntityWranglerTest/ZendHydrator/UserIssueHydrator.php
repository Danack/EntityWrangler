<?php

namespace EntityWranglerTest\ZendHydrator;

use EntityWranglerTest\TableGateway\IssueTableGateway;
use Zend\Hydrator\HydratorInterface;
use EntityWranglerTest\Model\User;
use EntityWranglerTest\Hydrator\HydratorException;

function extractValue(array $data, $keyName)
{
    if (array_key_exists($keyName, $data) === true) {
        return $data[$keyName];
    }

    throw new HydratorException("Missing key '$keyName' in data ".var_export($data, true));
}

class UserIssueHydrator implements HydratorInterface
{
    private $issueTableGateway;

    public function __construct(IssueTableGateway $issueTableGateway)
    {
        $this->issueTableGateway = $issueTableGateway;
    }
    
    public function hydrate(array $data, $user)
    {
        if (!$user instanceof User) {
            throw new \Exception("Well that's messed up.");
        }
        
        //$user = new User();
        $user->userId = extractValue($data, 'issue_id');
        $user->firstName = extractValue($data, 'description');
        $user->lastName = extractValue($data, 'text');
        
        

        return $user;
    }
    
    public function extract($object)
    {
        throw new \Exception("not implemented.");
    }
}
