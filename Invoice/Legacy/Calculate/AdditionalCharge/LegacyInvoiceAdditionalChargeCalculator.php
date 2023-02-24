<?php
/**
 * SAM-9966: Optimize db queries for Public/Admin Invoice List/Edit
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Calculate\AdditionalCharge;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\AdditionalCharge\Internal\Load\DataProviderCreateTrait;

/**
 * Class InvoiceAdditionalChargeCalculator
 * @package Sam\Invoice\Legacy\Calculate\AdditionalCharge
 */
class LegacyInvoiceAdditionalChargeCalculator extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate invoice line item charge amount
     *
     * @param int $invoiceId (int) invoice.id
     * @param bool $isPerLot (bool)
     * @param bool $isLeuOfTax (bool)
     * @param bool $isPercentage (bool)
     * @param float $amount (float)
     * @param float $hammerPrice (float)
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcAdditionalCharge(
        int $invoiceId,
        bool $isPerLot,
        bool $isLeuOfTax,
        bool $isPercentage,
        float $amount,
        float $hammerPrice,
        bool $isReadOnlyDb = false
    ): float {
        $dataProvider = $this->createDataProvider();
        $chargeAmount = $amount;
        if ($isPerLot) {
            $totalSalesTax = $dataProvider->calcTotalSalesTaxApplied($invoiceId, $isReadOnlyDb);
            if (
                $isLeuOfTax
                && $totalSalesTax
            ) {
                return 0.;
            }

            if ($isPercentage) {
                $chargeAmount = ($hammerPrice * $amount) / 100;
            }
        } elseif ($isPercentage) {
            $totalHammerPrice = $dataProvider->calcTotalHammerPrice($invoiceId, $isReadOnlyDb);
            $chargeAmount = ($totalHammerPrice * $amount) / 100;
        }
        return $chargeAmount;
    }
}
