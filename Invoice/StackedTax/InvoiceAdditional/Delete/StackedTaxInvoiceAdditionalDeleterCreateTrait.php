<?php
/**
 * SAM-11000: Stacked Tax. New Invoice Edit page: Payments section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\InvoiceAdditional\Delete;

/**
 * Trait StackedTaxInvoiceAdditionalDeleterCreateTrait
 * @package Sam\Invoice\StackedTax\InvoiceAdditional\Delete
 */
trait StackedTaxInvoiceAdditionalDeleterCreateTrait
{
    protected ?StackedTaxInvoiceAdditionalDeleter $stackedTaxInvoiceAdditionalDeleter = null;

    /**
     * @return StackedTaxInvoiceAdditionalDeleter
     */
    protected function createStackedTaxInvoiceAdditionalDeleter(): StackedTaxInvoiceAdditionalDeleter
    {
        return $this->stackedTaxInvoiceAdditionalDeleter ?: StackedTaxInvoiceAdditionalDeleter::new();
    }

    /**
     * @param StackedTaxInvoiceAdditionalDeleter $stackedTaxInvoiceAdditionalDeleter
     * @return static
     * @internal
     */
    public function setStackedTaxInvoiceAdditionalDeleter(StackedTaxInvoiceAdditionalDeleter $stackedTaxInvoiceAdditionalDeleter): static
    {
        $this->stackedTaxInvoiceAdditionalDeleter = $stackedTaxInvoiceAdditionalDeleter;
        return $this;
    }
}
