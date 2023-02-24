<?php
/**
 * SAM-10997: Stacked Tax. New Invoice Edit page: Goods section (Invoice Items)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Setting;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class InvoiceSettingChecker
 * @package Sam\Invoice\Common\Setting
 */
class InvoiceSettingChecker extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isTaxRendering(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::INVOICE_ITEM_SALES_TAX, $accountId);
    }

    public function isTaxSeparatedRendering(int $accountId): bool
    {
        return $this->isTaxRendering($accountId)
            && $this->getSettingsManager()->get(Constants\Setting::INVOICE_ITEM_SEPARATE_TAX, $accountId);
    }

    public function isTaxUnitedRendering(int $accountId): bool
    {
        return $this->isTaxRendering($accountId)
            && !$this->getSettingsManager()->get(Constants\Setting::INVOICE_ITEM_SEPARATE_TAX, $accountId);
    }
}
