<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Credit;

/**
 * Trait InvoiceCreditApplierAwareTrait
 * @package Sam\Invoice\Common\Credit
 */
trait InvoiceCreditApplierAwareTrait
{
    /**
     * @var InvoiceCreditApplier|null
     */
    protected ?InvoiceCreditApplier $invoiceCreditApplier = null;

    /**
     * @return InvoiceCreditApplier
     */
    protected function getInvoiceCreditApplier(): InvoiceCreditApplier
    {
        if ($this->invoiceCreditApplier === null) {
            $this->invoiceCreditApplier = InvoiceCreditApplier::new();
        }
        return $this->invoiceCreditApplier;
    }

    /**
     * @param InvoiceCreditApplier $invoiceCreditApplier
     * @return static
     * @internal
     */
    public function setInvoiceCreditApplier(InvoiceCreditApplier $invoiceCreditApplier): static
    {
        $this->invoiceCreditApplier = $invoiceCreditApplier;
        return $this;
    }
}
