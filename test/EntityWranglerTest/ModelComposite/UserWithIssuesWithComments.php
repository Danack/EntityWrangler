<?php

namespace EntityWranglerTest\ModelComposite;

use EntityWranglerTest\Model\User;
use EntityWranglerTest\Model\Issue;

class UserWithIssuesWithComments
{
    /** @var  User */
    public $user;

    /** @var  IssueWithComments[] */
    public $issueWithComments;
}
