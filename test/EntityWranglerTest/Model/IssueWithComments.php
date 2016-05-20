<?php 

namespace EntityWranglerTest\Model;

/**
 * Auto-generated.
 */
class IssueWithComments
{

    /**
     * @var Issue
     */
    public $issue = null;

    /**
     * @var IssueComment[]
     */
    public $issueComments = null;

    public function __construct($issue, $issueComments)
    {
        $this->issue = $issue;
        $this->issueComments = $issueComments;
    }


}
