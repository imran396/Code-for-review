<?php
/**
 * SAM-10939: Stacked Tax. Invoice Management pages. New Invoice Edit page. The "Remove Taxes" action
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 6, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\RemoveTax;

/**
 * Trait StackedTaxInvoiceTaxRemoverCreateTrait
 * @package Sam\Invoice\StackedTax\RemoveTax
 */
trait StackedTaxInvoiceTaxRemoverCreateTrait
{
    protected ?StackedTaxInvoiceTaxRemover $stackedTaxInvoiceTaxRemover = null;

    /**
     * @return StackedTaxInvoiceTaxRemover
     */
    protected function createStackedTaxInvoiceTaxRemover(): StackedTaxInvoiceTaxRemover
    {
        return $this->stackedTaxInvoiceTaxRemover ?: StackedTaxInvoiceTaxRemover::new();
    }

    /**
     * @param StackedTaxInvoiceTaxRemover $stackedTaxInvoiceTaxRemover
     * @return $this
     * @internal
     */
    public function setStackedTaxInvoiceTaxRemover(StackedTaxInvoiceTaxRemover $stackedTaxInvoiceTaxRemover): static
    {
        $this->stackedTaxInvoiceTaxRemover = $stackedTaxInvoiceTaxRemover;
        return $this;
    }
}
