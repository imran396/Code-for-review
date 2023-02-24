<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\User;

use User;

class UserWriteRepository extends AbstractUserWriteRepository
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveWithSelfModifier(User $user): void
    {
        $this->saveEntityWithModifier($user, $user->Id);
    }
}
