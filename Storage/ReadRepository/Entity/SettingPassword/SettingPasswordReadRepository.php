<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingPassword;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingPasswordReadRepository extends AbstractSettingPasswordReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
