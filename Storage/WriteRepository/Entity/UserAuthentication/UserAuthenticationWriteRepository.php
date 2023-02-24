<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserAuthentication;

use UserAuthentication;

class UserAuthenticationWriteRepository extends AbstractUserAuthenticationWriteRepository
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveWithSelfModifier(UserAuthentication $userAuthentication): void
    {
        $this->saveEntityWithModifier($userAuthentication, $userAuthentication->UserId);
    }
}
