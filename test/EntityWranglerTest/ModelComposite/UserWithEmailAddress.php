<?php

namespace EntityWranglerTest\ModelComposite;

use EntityWranglerTest\Model\User;
use EntityWranglerTest\Model\EmailAddress;

class UserWithEmailAddress
{
    /** @var  User */
    public $user;

    /** @var  EmailAddress */
    public $emailAddress;
}
