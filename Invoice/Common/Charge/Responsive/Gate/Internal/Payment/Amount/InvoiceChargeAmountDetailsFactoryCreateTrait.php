<?php
/**
 * SAM-11338: Stacked Tax. Public page Invoice with CC surcharge and Service tax on surcharge
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amount;

/**
 * Trait InvoiceChargeAmountDetailsFactoryCreateTrait
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amount
 */
trait InvoiceChargeAmountDetailsFactoryCreateTrait
{
    protected ?InvoiceChargeAmountDetailsFactory $invoiceChargeAmountDetailsFactory = null;

    /**
     * @return InvoiceChargeAmountDetailsFactory
     */
    protected function createInvoiceChargeAmountDetailsFactory(): InvoiceChargeAmountDetailsFactory
    {
        return $this->invoiceChargeAmountDetailsFactory ?: InvoiceChargeAmountDetailsFactory::new();
    }

    /**
     * @param InvoiceChargeAmountDetailsFactory $invoiceChargeAmountDetailsFactory
     * @return static
     * @internal
     */
    public function setInvoiceChargeAmountDetailsFactory(InvoiceChargeAmountDetailsFactory $invoiceChargeAmountDetailsFactory): static
    {
        $this->invoiceChargeAmountDetailsFactory = $invoiceChargeAmountDetailsFactory;
        return $this;
    }
}
