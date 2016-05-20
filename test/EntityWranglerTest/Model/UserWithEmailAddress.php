<?php 

namespace EntityWranglerTest\Model;

/**
 * Auto-generated.
 */
class UserWithEmailAddress
{

    /**
     * @var User
     */
    public $user = null;

    /**
     * @var EmailAddress
     */
    public $emailAddress = null;

    public function __construct($user, $emailAddress)
    {
        $this->user = $user;
        $this->emailAddress = $emailAddress;
    }


}
