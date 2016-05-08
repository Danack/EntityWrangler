<?php

namespace EntityWranglerTest\Hydrator;

use EntityWranglerTest\ModelComposite\UserWithEmailAddress;
use EntityWrangler\Query\QueriedEntity;

class UserWithEmailHydrator implements Hydrator
{
    /** @var QueriedEntity  */
    private $userEntity;

    /** @var QueriedEntity  */
    private $emailAddressEntity;

    public function __construct(QueriedEntity $userEntity, QueriedEntity $emailAddressEntity)
    {
        $this->userEntity = $userEntity;
        $this->emailAddressEntity = $emailAddressEntity;
    }

    public function hydrate(array $data, HydratorRegistry $hydratorRegistry)
    {
        $userWithEmailAddress = new UserWithEmailAddress();
        $userWithEmailAddress->emailAddress = $hydratorRegistry->hydrate(
            'EntityWranglerTest\Model\EmailAddress',
            $data,
            $this->emailAddressEntity->getAlias().'_'
        );
        $userWithEmailAddress->user = $hydratorRegistry->hydrate(
            'EntityWranglerTest\Model\User',
            $data,
            $this->userEntity->getAlias().'_'
        );

        return $userWithEmailAddress;
    }
}
