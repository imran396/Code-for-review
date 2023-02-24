<?php
/**
 *
 * SAM-4724: Invoice Line item deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-23
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Delete;

/**
 * Trait InvoiceLineItemDeleterAwareTrait
 * @package Sam\Invoice\Common\LineItem\Delete
 */
trait InvoiceLineItemDeleterAwareTrait
{
    /**
     * @var InvoiceLineItemDeleter|null
     */
    protected ?InvoiceLineItemDeleter $invoiceLineItemDeleter = null;

    /**
     * @return InvoiceLineItemDeleter
     */
    protected function getInvoiceLineItemDeleter(): InvoiceLineItemDeleter
    {
        if ($this->invoiceLineItemDeleter === null) {
            $this->invoiceLineItemDeleter = InvoiceLineItemDeleter::new();
        }
        return $this->invoiceLineItemDeleter;
    }

    /**
     * @param InvoiceLineItemDeleter $invoiceLineItemDeleter
     * @return static
     * @internal
     */
    public function setInvoiceLineItemDeleter(InvoiceLineItemDeleter $invoiceLineItemDeleter): static
    {
        $this->invoiceLineItemDeleter = $invoiceLineItemDeleter;
        return $this;
    }
}
