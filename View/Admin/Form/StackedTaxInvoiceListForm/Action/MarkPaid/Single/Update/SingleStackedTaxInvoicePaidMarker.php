<?php
/**
 * SAM-11079: Stacked Tax. Tax aggregation. Admin Invoice List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Update;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;

/**
 * Class SingleStackedTaxInvoicePaidMarker
 * @package Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Update
 */
class SingleStackedTaxInvoicePaidMarker extends CustomizableClass
{
    use InvoiceLoaderAwareTrait;
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
     * @param int $invoiceId
     * @param int $editorUserId
     * @return Invoice
     */
    public function markPaid(int $invoiceId, int $editorUserId): Invoice
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }

        $invoice->toPaid();
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        return $invoice;
    }

}
