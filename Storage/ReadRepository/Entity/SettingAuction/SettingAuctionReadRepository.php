<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingAuction;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingAuctionReadRepository extends AbstractSettingAuctionReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
