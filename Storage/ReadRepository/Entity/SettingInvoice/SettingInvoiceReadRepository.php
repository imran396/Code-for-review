<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingInvoice;

use Sam\Settings\Repository\SettingsReadRepositoryInterface;

class SettingInvoiceReadRepository extends AbstractSettingInvoiceReadRepository implements SettingsReadRepositoryInterface
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
