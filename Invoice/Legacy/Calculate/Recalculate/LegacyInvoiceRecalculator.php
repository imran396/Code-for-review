<?php
/**
 * This service handles action of the "Recalculate" button click
 *
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

namespace Sam\Invoice\Legacy\Calculate\Recalculate;

use Invoice;
use InvoiceItem;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculatorAwareTrait;
use Sam\Core\Entity\Model\Invoice\Subtotal\InvoiceItemSubtotalPureCalculator;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Legacy\Calculate\Summary\LegacyInvoiceSummaryCalculatorAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;

/**
 * Class InvoiceRecalculator
 * @package Sam\Invoice\Legacy\Calculate\Recalculate
 */
class LegacyInvoiceRecalculator extends CustomizableClass
{
    use BuyersPremiumCalculatorAwareTrait;
    use LegacyInvoiceCalculatorAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use LegacyInvoiceSummaryCalculatorAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Invoice $invoice
     * @param InvoiceItem[] $invoiceItems
     * @param int $editorUserId
     * @return void
     */
    public function recalculate(Invoice $invoice, array $invoiceItems, int $editorUserId): void
    {
        $invoiceCalculator = $this->getLegacyInvoiceCalculator();
        $invoiceTaxPureCalculator = InvoiceTaxPureCalculator::new();
        foreach ($invoiceItems as $invoiceItem) {
            $hammerPrice = (float)$invoiceItem->HammerPrice;
            $buyersPremium = $this->getBuyersPremiumCalculator()->calculate(
                (int)$invoiceItem->LotItemId, // JIC
                $invoiceItem->AuctionId,
                $invoiceItem->WinningBidderId,
                $invoice->AccountId,
                $hammerPrice
            );

            $taxResult = $invoiceCalculator->detectApplicableSalesTax(
                (int)$invoiceItem->WinningBidderId, // JIC
                $invoiceItem->LotItemId,
                $invoiceItem->AuctionId
            );

            $salesTaxAmount = $invoiceTaxPureCalculator->calcSalesTaxApplied(
                $hammerPrice,
                $buyersPremium,
                $taxResult->percent,
                $taxResult->application
            );

            $invoiceItem->BuyersPremium = $buyersPremium;
            $invoiceItem->applyApplicableSaleTaxResult($taxResult);
            $invoiceItem->Subtotal = InvoiceItemSubtotalPureCalculator::new()
                ->calc($hammerPrice, $buyersPremium, $salesTaxAmount);
            $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);
        }

        $this->getLegacyInvoiceSummaryCalculator()->recalculate($invoice->Id, $editorUserId);
    }

}
