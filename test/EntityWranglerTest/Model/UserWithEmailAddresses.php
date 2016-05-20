<?php 

namespace EntityWranglerTest\Model;

/**
 * Auto-generated.
 */
class UserWithEmailAddresses
{

    /**
     * @var User
     */
    public $user = null;

    /**
     * @var EmailAddress[]
     */
    public $emailAddresses = null;

    public function __construct($user, $emailAddresses)
    {
        $this->user = $user;
        $this->emailAddresses = $emailAddresses;
    }


}
