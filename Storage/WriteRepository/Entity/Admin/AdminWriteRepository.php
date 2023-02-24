<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Admin;

use Admin;

class AdminWriteRepository extends AbstractAdminWriteRepository
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveWithSelfModifier(Admin $admin): void
    {
        $this->saveEntityWithModifier($admin, $admin->UserId);
    }
}
