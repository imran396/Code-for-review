<?php
/**
 * SAM-4374: Invoice Existence Checker class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/4/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Validate;

/**
 * Trait InvoiceExistenceCheckerAwareTrait
 * @package Sam\Invoice\Common\Validate
 */
trait InvoiceExistenceCheckerAwareTrait
{
    /**
     * @var InvoiceExistenceChecker|null
     */
    protected ?InvoiceExistenceChecker $invoiceExistenceChecker = null;

    /**
     * @return InvoiceExistenceChecker
     */
    protected function getInvoiceExistenceChecker(): InvoiceExistenceChecker
    {
        if ($this->invoiceExistenceChecker === null) {
            $this->invoiceExistenceChecker = InvoiceExistenceChecker::new();
        }
        return $this->invoiceExistenceChecker;
    }

    /**
     * @param InvoiceExistenceChecker $invoiceExistenceChecker
     * @return static
     * @internal
     */
    public function setInvoiceExistenceChecker(InvoiceExistenceChecker $invoiceExistenceChecker): static
    {
        $this->invoiceExistenceChecker = $invoiceExistenceChecker;
        return $this;
    }
}
