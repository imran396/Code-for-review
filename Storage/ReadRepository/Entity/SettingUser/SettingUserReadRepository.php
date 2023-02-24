<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingUser;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingUserReadRepository extends AbstractSettingUserReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
