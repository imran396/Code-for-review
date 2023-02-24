<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingUi;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingUiReadRepository extends AbstractSettingUiReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
