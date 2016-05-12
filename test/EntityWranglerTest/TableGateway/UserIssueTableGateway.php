<?php

namespace EntityWranglerTest\TableGateway;

use EntityWranglerTest\Model\User;
use Zend\Hydrator\Aggregate\AggregateHydrator;
use EntityWranglerTest\Model\UserWithIssues;
use EntityWranglerTest\TableGateway\IssueTableGateway;
use EntityWranglerTest\TableGateway\UserTableGateway;

class UserIssueTableGateway
{
    private $data;

    /** @var  AggregateHydrator */
    private $aggregateHydrator;

    /** @var  UserTableGateway */
    private $userTableGateway;

    /** @var IssueTableGateway */
    private $issueTableGateway;

    public static function fromResultSet(
        IssueTableGateway $issueTableGateway,
        UserTableGateway $userTableGateway,
        array $data
    ) {
        $instance = new self();
        $instance->data = $data;
        $instance->userTableGateway = $userTableGateway;
        $instance->issueTableGateway = $issueTableGateway;

        return $instance;
    }

    /** @return UserWithIssues[] */
    public function fetchAll()
    {
        $userWithIssueList = [];
        $users = $this->userTableGateway->fetchAll();

        foreach ($users as $user) {
            $filteredData = $this->userTableGateway->filterDataByUserId($this->data, $user->userId);
            $issues = $this->issueTableGateway->findAllByUserId($filteredData);
            $userWithIssues = new UserWithIssues($user, $issues);
            $userWithIssueList[] = $userWithIssues;
        }

        return $userWithIssueList;
    }
}
