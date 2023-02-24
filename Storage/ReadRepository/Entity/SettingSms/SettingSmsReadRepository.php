<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSms;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingSmsReadRepository extends AbstractSettingSmsReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
