<?php
/**
 * SAM-10939: Stacked Tax. Invoice Management pages. New Invoice Edit page. The "Remove Taxes" action
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 6, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\RemoveTax;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\StackedTax\InvoiceAdditional\Load\InvoiceAdditionalLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;

/**
 * Class StackedTaxInvoiceTaxRemover
 * @package Sam\Invoice\StackedTax\RemoveTax
 */
class StackedTaxInvoiceTaxRemover extends CustomizableClass
{
    use InvoiceAdditionalLoaderCreateTrait;
    use InvoiceAdditionalWriteRepositoryAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
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
     * Drop all assigned tax schemas from invoiced items and services
     * @param Invoice $invoice
     * @param int $editorUserId
     * @return void
     */
    public function remove(Invoice $invoice, int $editorUserId): void
    {
        $invoiceAdditionals = $this->createInvoiceAdditionalLoader()->loadByInvoiceId($invoice->Id);
        foreach ($invoiceAdditionals as $invoiceAdditional) {
            $invoiceAdditional->TaxAmount = 0.;
            $invoiceAdditional->CountryTaxAmount = 0.;
            $invoiceAdditional->StateTaxAmount = 0.;
            $invoiceAdditional->CountyTaxAmount = 0.;
            $invoiceAdditional->CityTaxAmount = 0.;
            $invoiceAdditional->TaxSchemaId = null;
            $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);
        }

        $invoiceItems = $this->getInvoiceItemLoader()->loadByInvoiceId($invoice->Id);
        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItem->BpTaxAmount = 0.;
            $invoiceItem->BpTaxSchemaId = null;
            $invoiceItem->HpTaxAmount = 0.;
            $invoiceItem->HpTaxSchemaId = null;
            $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);
        }
    }
}
