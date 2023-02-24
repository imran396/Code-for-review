<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Payment;

/**
 * Trait InvoicePaymentMethodManagerAwareTrait
 * @package Sam\Invoice\Common\Payment
 */
trait InvoicePaymentMethodManagerAwareTrait
{
    /**
     * @var InvoicePaymentMethodManager|null
     */
    protected ?InvoicePaymentMethodManager $invoicePaymentMethodManager = null;

    /**
     * @return InvoicePaymentMethodManager
     */
    protected function getInvoicePaymentMethodManager(): InvoicePaymentMethodManager
    {
        if ($this->invoicePaymentMethodManager === null) {
            $this->invoicePaymentMethodManager = InvoicePaymentMethodManager::new();
        }
        return $this->invoicePaymentMethodManager;
    }

    /**
     * @param InvoicePaymentMethodManager $invoicePaymentMethodManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInvoicePaymentMethodManager(InvoicePaymentMethodManager $invoicePaymentMethodManager): static
    {
        $this->invoicePaymentMethodManager = $invoicePaymentMethodManager;
        return $this;
    }
}
