<?php

namespace EntityWranglerTest\Hydrator;

use EntityWranglerTest\ModelComposite\UserWithIssuesWithComments;
use EntityWrangler\Query\QueriedEntity;
use EntityWrangler\SafeAccess;

class UserWithIssuesHydrator implements Hydrator
{
    use SafeAccess;
    
    /** @var QueriedEntity  */
    private $userEntity;

    /** @var QueriedEntity  */
    private $issueEntity;
    /** @var QueriedEntity  */
    private $issueCommentEntity;

    public function __construct(
        QueriedEntity $userEntity,
        QueriedEntity $issueEntity
    ) {
        $this->userEntity = $userEntity;
        $this->issueEntity = $issueEntity;
    }

    public function hydrate(array $data, HydratorRegistry $hydratorRegistry)
    {
        $users = [];
        $issues = [];
        
        $user = null;

        foreach ($data as $item) { 
            $user = $hydratorRegistry->hydrate(
                'EntityWranglerTest\Model\User',
                $item,
                $this->userEntity->getAlias().'_'
            );
            $users[$user->userId] = $user;
            $issue = $hydratorRegistry->hydrate(
                'EntityWranglerTest\Model\Issue',
                $item,
                $this->issueEntity->getAlias().'_'
            );
            
            $issues[$issue->issueId] = $issue;
        }
        
        $userWithIssues = new UserWithIssues();
        
        $userWithIssues->user = $user;
        $userWithIssues->issues = $issues; 

        return $userWithIssues;
    }
}
