<?php

namespace EntityWranglerTest\ModelComposite;

use EntityWranglerTest\Model\IssueComment;
use EntityWranglerTest\Model\User;
use EntityWranglerTest\Model\Issue;

class IssueCommentWithUser
{
    /** @var IssueComment */
    public $issueComment;

    /** @var  User */
    public $user;
}
