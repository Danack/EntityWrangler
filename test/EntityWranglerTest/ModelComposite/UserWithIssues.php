<?php

namespace EntityWranglerTest\ModelComposite;

use EntityWranglerTest\Model\Issue;
use EntityWranglerTest\Model\User;

class UserWithIssues
{
    /** User */
    public $user;

    /** @var  Issue[] */
    public $issues;

    public function __construct(User $user, array $issues)
    {
        $this->user = $user;
        $this->issues = $issues;
    }
}
