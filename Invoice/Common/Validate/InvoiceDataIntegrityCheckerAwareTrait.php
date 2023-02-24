<?php
/**
 * Trait for InvoiceDataIntegrityChecker
 *
 * SAM-5073: Data integrity checker - an lot_item shall only be in one non paid/canceled invoice at a time
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/12/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Validate;

/**
 * Trait InvoiceDataIntegrityCheckerAwareTrait
 * @package Sam\Invoice\Common\Validate
 */
trait InvoiceDataIntegrityCheckerAwareTrait
{
    /**
     * @var InvoiceDataIntegrityChecker|null
     */
    protected ?InvoiceDataIntegrityChecker $invoiceDataIntegrityChecker = null;

    /**
     * @return InvoiceDataIntegrityChecker
     */
    protected function getInvoiceDataIntegrityChecker(): InvoiceDataIntegrityChecker
    {
        if ($this->invoiceDataIntegrityChecker === null) {
            $this->invoiceDataIntegrityChecker = InvoiceDataIntegrityChecker::new();
        }
        return $this->invoiceDataIntegrityChecker;
    }

    /**
     * @param InvoiceDataIntegrityChecker $invoiceDataIntegrityChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInvoiceDataIntegrityChecker(InvoiceDataIntegrityChecker $invoiceDataIntegrityChecker): static
    {
        $this->invoiceDataIntegrityChecker = $invoiceDataIntegrityChecker;
        return $this;
    }
}
