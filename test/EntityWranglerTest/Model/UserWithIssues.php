<?php 

namespace EntityWranglerTest\Model;

/**
 * Auto-generated.
 */
class UserWithIssues
{

    /**
     * @var User
     */
    public $user = null;

    /**
     * @var Issue[]
     */
    public $issues = null;

    public function __construct($user, $issues)
    {
        $this->user = $user;
        $this->issues = $issues;
    }


}
