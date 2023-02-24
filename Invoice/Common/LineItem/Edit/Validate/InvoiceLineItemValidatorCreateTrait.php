<?php
/**
 * SAM-9454: Refactor Invoice Line item editor for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Edit\Validate;

/**
 * Trait InvoiceLineItemValidatorCreateTrait
 * @package Sam\Invoice\Common\LineItem\Edit\Validate
 */
trait InvoiceLineItemValidatorCreateTrait
{
    /**
     * @var InvoiceLineItemValidator|null
     */
    protected ?InvoiceLineItemValidator $invoiceLineItemValidator = null;

    /**
     * @return InvoiceLineItemValidator
     */
    protected function createInvoiceLineItemValidator(): InvoiceLineItemValidator
    {
        return $this->invoiceLineItemValidator ?: InvoiceLineItemValidator::new();
    }

    /**
     * @param InvoiceLineItemValidator $invoiceLineItemValidator
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setInvoiceLineItemValidator(InvoiceLineItemValidator $invoiceLineItemValidator): static
    {
        $this->invoiceLineItemValidator = $invoiceLineItemValidator;
        return $this;
    }
}
