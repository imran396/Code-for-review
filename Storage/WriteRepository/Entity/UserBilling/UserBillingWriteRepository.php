<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserBilling;

use UserBilling;

class UserBillingWriteRepository extends AbstractUserBillingWriteRepository
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveWithSelfModifier(UserBilling $userBilling): void
    {
        $this->saveEntityWithModifier($userBilling, $userBilling->UserId);
    }
}
