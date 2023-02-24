<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingPaypal;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingBillingPaypalReadRepository extends AbstractSettingBillingPaypalReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
