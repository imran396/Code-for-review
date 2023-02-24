<?php
/**
 * SAM-11000: Stacked Tax. New Invoice Edit page: Payments section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\DeletePayment;

/**
 * Trait InvoicePaymentDeleterCreateTrait
 * @package Sam\Invoice\StackedTax\DeletePayment
 */
trait InvoicePaymentDeleterCreateTrait
{
    protected ?InvoicePaymentDeleter $invoicePaymentDeleter = null;

    /**
     * @return InvoicePaymentDeleter
     */
    protected function createInvoicePaymentDeleter(): InvoicePaymentDeleter
    {
        return $this->invoicePaymentDeleter ?: InvoicePaymentDeleter::new();
    }

    /**
     * @param InvoicePaymentDeleter $invoicePaymentDeleter
     * @return static
     * @internal
     */
    public function setInvoicePaymentDeleter(InvoicePaymentDeleter $invoicePaymentDeleter): static
    {
        $this->invoicePaymentDeleter = $invoicePaymentDeleter;
        return $this;
    }
}
