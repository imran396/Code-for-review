<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserLogin;

use UserLogin;

class UserLoginWriteRepository extends AbstractUserLoginWriteRepository
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveWithSelfModifier(UserLogin $userLogin): void
    {
        $this->saveEntityWithModifier($userLogin, $userLogin->UserId);
    }
}
