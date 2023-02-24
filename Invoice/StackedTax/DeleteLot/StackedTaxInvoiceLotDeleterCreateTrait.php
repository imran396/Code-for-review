<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\DeleteLot;

/**
 * Trait StackedTaxInvoiceLotDeleterCreateTrait
 * @package Sam\Invoice\StackedTax\DeleteLot
 */
trait StackedTaxInvoiceLotDeleterCreateTrait
{
    protected ?StackedTaxInvoiceLotDeleter $stackedTaxInvoiceLotDeleter = null;

    /**
     * @return StackedTaxInvoiceLotDeleter
     */
    protected function createStackedTaxInvoiceLotDeleter(): StackedTaxInvoiceLotDeleter
    {
        return $this->stackedTaxInvoiceLotDeleter ?: StackedTaxInvoiceLotDeleter::new();
    }

    /**
     * @param StackedTaxInvoiceLotDeleter $stackedTaxInvoiceLotDeleter
     * @return static
     * @internal
     */
    public function setStackedTaxInvoiceLotDeleter(StackedTaxInvoiceLotDeleter $stackedTaxInvoiceLotDeleter): static
    {
        $this->stackedTaxInvoiceLotDeleter = $stackedTaxInvoiceLotDeleter;
        return $this;
    }
}
