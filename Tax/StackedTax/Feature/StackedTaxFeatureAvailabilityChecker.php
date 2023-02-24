<?php
/**
 * SAM-10806: Stacked Tax. Feature configuration
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Feature;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class StackedTaxFeatureAvailabilityChecker
 * @package Sam\Tax\StackedTax\Feature
 */
class StackedTaxFeatureAvailabilityChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->cfg()->get('core->tax->stackedTax->enabled');
    }

    /**
     * Check if feature is enabled and the Stacked Tax strategy is chosen for invoice generation by default at some account.
     * @param int $accountId
     * @return bool
     */
    public function isStackedTaxDesignationForInvoice(int $accountId): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $invoiceTaxDesignationStrategy = $this->getSettingsManager()
            ->get(Constants\Setting::INVOICE_TAX_DESIGNATION_STRATEGY, $accountId);
        return $invoiceTaxDesignationStrategy === Constants\Invoice::TDS_STACKED_TAX;
    }
}
