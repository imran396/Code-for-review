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

namespace Sam\Invoice\StackedTax\Calculate\AdditionalCharge;

use Invoice;
use Sam\Core\Service\CustomizableClass;

class StackedTaxInvoiceAdditionalChargeCalculator extends CustomizableClass
{
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
     * @param Invoice $invoice
     * @param bool $isPerLot
     * @param bool $isLeuOfTax
     * @param bool $isPercentage
     * @param float $lineItemAmount
     * @param float $itemHammerPrice
     * @return float
     */
    public function calcAdditionalCharge(
        Invoice $invoice,
        bool $isPerLot,
        bool $isLeuOfTax,
        bool $isPercentage,
        float $lineItemAmount,
        float $itemHammerPrice
    ): float {
        $chargeAmount = $lineItemAmount;
        if ($isPerLot) {
            if (
                $isLeuOfTax
                && (
                    $invoice->HpTaxTotal
                    || $invoice->BpTaxTotal
                )
            ) {
                return 0.;
            }

            if ($isPercentage) {
                $chargeAmount = ($itemHammerPrice * $lineItemAmount) / 100;
            }
        } elseif ($isPercentage) {
            $chargeAmount = ($invoice->BidTotal * $lineItemAmount) / 100;
        }
        return $chargeAmount;
    }
}
