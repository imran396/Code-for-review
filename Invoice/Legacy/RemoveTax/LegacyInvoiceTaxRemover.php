<?php
/**
 * SAM-10901: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract the "Remove Taxes" button action
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\RemoveTax;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Summary\LegacyInvoiceSummaryCalculatorAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;

/**
 * Class InvoiceTaxRemover
 * @package Sam\Invoice\Legacy\RemoveTax
 */
class LegacyInvoiceTaxRemover extends CustomizableClass
{
    use InvoiceItemReadRepositoryCreateTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use LegacyInvoiceSummaryCalculatorAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;

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
     * @param int $editorUserId
     * @return void
     */
    public function remove(Invoice $invoice, int $editorUserId): void
    {
        $invoiceItems = $this->createInvoiceItemReadRepository()
            ->filterInvoiceId($invoice->Id)
            ->loadEntities();
        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItem->SalesTax = 0.;
            $invoiceItem->Subtotal = $invoiceItem->HammerPrice + $invoiceItem->BuyersPremium;
            $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);
        }

        $invoice->TaxChargesRate = 0.;
        $invoice->TaxFeesRate = 0.;
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

        $this->getLegacyInvoiceSummaryCalculator()->recalculate($invoice->Id, $editorUserId);
    }
}
