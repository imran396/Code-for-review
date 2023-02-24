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

namespace Sam\Invoice\Common\Threshold;

/**
 * Trait InvoiceThresholdCheckerAwareTrait
 * @package Sam\Invoice\Common\Threshold
 */
trait InvoiceThresholdCheckerAwareTrait
{
    /**
     * @var InvoiceThresholdChecker|null
     */
    protected ?InvoiceThresholdChecker $invoiceThresholdChecker = null;

    /**
     * @return InvoiceThresholdChecker
     */
    protected function getInvoiceThresholdChecker(): InvoiceThresholdChecker
    {
        if ($this->invoiceThresholdChecker === null) {
            $this->invoiceThresholdChecker = InvoiceThresholdChecker::new();
        }
        return $this->invoiceThresholdChecker;
    }

    /**
     * @param InvoiceThresholdChecker $invoiceThresholdChecker
     * @return static
     * @internal
     */
    public function setInvoiceThresholdChecker(InvoiceThresholdChecker $invoiceThresholdChecker): static
    {
        $this->invoiceThresholdChecker = $invoiceThresholdChecker;
        return $this;
    }
}
