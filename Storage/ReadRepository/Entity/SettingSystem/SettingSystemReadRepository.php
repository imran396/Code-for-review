<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSystem;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingSystemReadRepository extends AbstractSettingSystemReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
