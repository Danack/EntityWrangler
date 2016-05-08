<?php

namespace EntityWranglerTest\ModelComposite;

use EntityWranglerTest\Model\User;
use EntityWranglerTest\Model\Issue;
use EntityWranglerTest\Model\IssueComment;

class IssueWithComments
{
    /** @var  Issue */
    public $issue;
    
    /** @var  IssueComment[] */
    public $issues;
}
