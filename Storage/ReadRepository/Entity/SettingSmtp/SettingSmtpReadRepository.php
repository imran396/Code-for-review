<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSmtp;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingSmtpReadRepository extends AbstractSettingSmtpReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
