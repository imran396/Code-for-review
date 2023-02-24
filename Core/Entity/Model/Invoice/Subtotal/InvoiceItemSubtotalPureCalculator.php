<?php
/**
 * SAM-10905: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract calculation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Invoice\Subtotal;

use InvoiceItem;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemSubtotalPureCalculator
 * @package Sam\Core\Entity\Model\Invoice\Subtotal
 */
class InvoiceItemSubtotalPureCalculator extends CustomizableClass
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
     * Calculate Subtotal of Invoiced Item by formula: HP + BP + Sales Tax
     * @param float|null $hammerPrice
     * @param float|null $buyersPremium
     * @param float|null $salesTaxAmount calculated applied sales tax amount (not percent value)
     * @return float
     */
    public function calc(
        ?float $hammerPrice,
        ?float $buyersPremium,
        ?float $salesTaxAmount
    ): float {
        return $hammerPrice + $buyersPremium + $salesTaxAmount;
    }

    /**
     * Calculate Subtotal of Invoiced Item by formula: HP + BP + Sales Tax
     * @param InvoiceItem $invoiceItem
     * @return float
     */
    public function calcByInvoiceItem(InvoiceItem $invoiceItem): float
    {
        $salesTaxAmount = InvoiceTaxPureCalculator::new()->calcSalesTaxAppliedByInvoiceItem($invoiceItem);
        $subtotal = $this->calc($invoiceItem->HammerPrice, $invoiceItem->BuyersPremium, $salesTaxAmount);
        return $subtotal;
    }

}
