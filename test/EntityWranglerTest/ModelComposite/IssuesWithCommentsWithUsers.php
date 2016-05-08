<?php

namespace EntityWranglerTest\ModelComposite;

use EntityWranglerTest\Model\User;
use EntityWranglerTest\Model\Issue;

class IssuesWithCommentsAndUser
{
    /** @var  Issue */
    public $issue;

    /** @var  IssueCommentWithUser[] */
    public $issueCommentWithUser;
}
