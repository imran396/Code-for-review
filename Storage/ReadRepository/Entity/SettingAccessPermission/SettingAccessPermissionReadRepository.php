<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingAccessPermission;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingAccessPermissionReadRepository extends AbstractSettingAccessPermissionReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
