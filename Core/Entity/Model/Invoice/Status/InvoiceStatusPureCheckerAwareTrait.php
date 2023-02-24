<?php
/**
 * SAM-6830: Enrich Invoice entity
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Invoice\Status;

/**
 * Trait InvoiceStatusPureCheckerAwareTrait
 * @package Sam\Core\Entity\Model\Invoice\Status
 */
trait InvoiceStatusPureCheckerAwareTrait
{
    /**
     * @var InvoiceStatusPureChecker|null
     */
    protected ?InvoiceStatusPureChecker $invoiceStatusPureChecker = null;

    /**
     * @return InvoiceStatusPureChecker
     */
    protected function getInvoiceStatusPureChecker(): InvoiceStatusPureChecker
    {
        if ($this->invoiceStatusPureChecker === null) {
            $this->invoiceStatusPureChecker = InvoiceStatusPureChecker::new();
        }
        return $this->invoiceStatusPureChecker;
    }

    /**
     * @param InvoiceStatusPureChecker $invoiceStatusPureChecker
     * @return $this
     * @internal
     */
    public function setInvoiceStatusPureChecker(InvoiceStatusPureChecker $invoiceStatusPureChecker): static
    {
        $this->invoiceStatusPureChecker = $invoiceStatusPureChecker;
        return $this;
    }
}
