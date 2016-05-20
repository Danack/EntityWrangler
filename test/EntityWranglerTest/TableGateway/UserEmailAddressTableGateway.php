<?php

namespace EntityWranglerTest\TableGateway;

use EntityWranglerTest\Model\User;

use EntityWranglerTest\Model\UserWithEmailAddress;
use EntityWranglerTest\Model\UserWithEmailAddresses;


class UserEmailAddressTableGateway
{
    private $data;

    /** @var  UserTableGateway */
    private $userTableGateway;

    /** @var EmailAddressTableGateway */
    private $emailAddressTableGateway;

    public static function fromResultSet(
        EmailAddressTableGateway $emailAddressTableGateway,
        UserTableGateway $userTableGateway,
        array $data
    ) {
        $instance = new self();
        $instance->data = $data;
        $instance->userTableGateway = $userTableGateway;
        $instance->emailAddressTableGateway = $emailAddressTableGateway;

        return $instance;
    }

    /** @return UserWithEmailAddresses[] */
    public function fetchAll()
    {
        $userWithIssueList = [];
        $users = $this->userTableGateway->fetchAll();

        foreach ($users as $user) {
            $filteredData = $this->userTableGateway->filterDataByUserId($this->data, $user->userId);
            $emails = $this->emailAddressTableGateway->findAllByUserId($filteredData);
            $userWithIssues = new UserWithEmailAddresses($user, $emails);
            $userWithIssueList[] = $userWithIssues;
        }

        return $userWithIssueList;
    }
}
