<?php

namespace EntityWranglerTest\Hydrator;

use EntityWranglerTest\Model\EmailAddress;

class EmailAddressHydrator implements Hydrator
{
    public function hydrate(array $data, HydratorRegistry $hydratorRegistry, $aliasPrefix)
    {
        $emailAddress = new EmailAddress();
        $emailAddress->emailAddressId = $hydratorRegistry->extractValue($data, $aliasPrefix.'email_address_id');
        $emailAddress->address = $hydratorRegistry->extractValue($data, $aliasPrefix.'address');

        return $emailAddress;
    }
}
