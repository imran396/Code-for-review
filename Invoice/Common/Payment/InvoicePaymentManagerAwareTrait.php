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
 * Trait InvoicePaymentManagerAwareTrait
 * @package Sam\Invoice\Common\Payment
 */
trait InvoicePaymentManagerAwareTrait
{
    /**
     * @var InvoicePaymentManager|null
     */
    protected ?InvoicePaymentManager $invoicePaymentManager = null;

    /**
     * @return InvoicePaymentManager
     */
    protected function getInvoicePaymentManager(): InvoicePaymentManager
    {
        if ($this->invoicePaymentManager === null) {
            $this->invoicePaymentManager = InvoicePaymentManager::new();
        }
        return $this->invoicePaymentManager;
    }

    /**
     * @param InvoicePaymentManager $invoicePaymentManager
     * @return static
     * @internal
     */
    public function setInvoicePaymentManager(InvoicePaymentManager $invoicePaymentManager): static
    {
        $this->invoicePaymentManager = $invoicePaymentManager;
        return $this;
    }

}
