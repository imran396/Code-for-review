<?php
/**
 * Invoice List Form Data Loader Create Trait
 *
 * SAM-6092: Refactor Invoice List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\StackedTaxInvoiceListForm\Load;

/**
 * Trait InvoiceListFormDataLoaderCreateTrait
 */
trait StackedTaxInvoiceListFormDataLoaderCreateTrait
{
    protected ?StackedTaxInvoiceListFormDataLoader $stackedTaxInvoiceListFormDataLoader = null;

    /**
     * @return StackedTaxInvoiceListFormDataLoader
     */
    protected function createStackedTaxInvoiceListFormDataLoader(): StackedTaxInvoiceListFormDataLoader
    {
        return $this->stackedTaxInvoiceListFormDataLoader ?: StackedTaxInvoiceListFormDataLoader::new();
    }

    /**
     * @param StackedTaxInvoiceListFormDataLoader $stackedTaxInvoiceListFormDataLoader
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setStackedTaxInvoiceListFormDataLoader(StackedTaxInvoiceListFormDataLoader $stackedTaxInvoiceListFormDataLoader): static
    {
        $this->stackedTaxInvoiceListFormDataLoader = $stackedTaxInvoiceListFormDataLoader;
        return $this;
    }
}
