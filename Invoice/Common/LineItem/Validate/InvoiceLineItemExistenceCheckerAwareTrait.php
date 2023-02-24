<?php
/**
 * SAM-4723: Invoice Line item editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/22/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Validate;

/**
 * Trait InvoiceLineItemExistenceCheckerAwareTrait
 * @package Sam\Invoice\Common\LineItem\Validate
 */
trait InvoiceLineItemExistenceCheckerAwareTrait
{
    /**
     * @var InvoiceLineItemExistenceChecker|null
     */
    protected ?InvoiceLineItemExistenceChecker $invoiceLineItemExistenceChecker = null;

    /**
     * @return InvoiceLineItemExistenceChecker
     */
    protected function getInvoiceLineItemExistenceChecker(): InvoiceLineItemExistenceChecker
    {
        if ($this->invoiceLineItemExistenceChecker === null) {
            $this->invoiceLineItemExistenceChecker = InvoiceLineItemExistenceChecker::new();
        }
        return $this->invoiceLineItemExistenceChecker;
    }

    /**
     * @param InvoiceLineItemExistenceChecker $invoiceLineItemExistenceChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInvoiceLineItemExistenceChecker(InvoiceLineItemExistenceChecker $invoiceLineItemExistenceChecker): static
    {
        $this->invoiceLineItemExistenceChecker = $invoiceLineItemExistenceChecker;
        return $this;
    }
}
