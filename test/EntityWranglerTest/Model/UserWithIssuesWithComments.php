<?php 

namespace EntityWranglerTest\Model;

/**
 * Auto-generated.
 */
class UserWithIssuesWithComments
{

    /**
     * @var User
     */
    public $user = null;

    /**
     * @var IssueWithComments[]
     */
    public $issueWithComments = null;

    public function __construct($user, $issueWithComments)
    {
        $this->user = $user;
        $this->issueWithComments = $issueWithComments;
    }


}
