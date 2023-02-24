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

namespace Sam\View\Admin\Form\InvoiceListForm\Load;

/**
 * Trait InvoiceListFormDataLoaderCreateTrait
 */
trait InvoiceListFormDataLoaderCreateTrait
{
    protected ?InvoiceListFormDataLoader $invoiceListFormDataLoader = null;

    /**
     * @return InvoiceListFormDataLoader
     */
    protected function createInvoiceListFormDataLoader(): InvoiceListFormDataLoader
    {
        $invoiceListFormDataLoader = $this->invoiceListFormDataLoader ?: InvoiceListFormDataLoader::new();
        return $invoiceListFormDataLoader;
    }

    /**
     * @param InvoiceListFormDataLoader $invoiceListFormDataLoader
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setInvoiceListFormDataLoader(InvoiceListFormDataLoader $invoiceListFormDataLoader): static
    {
        $this->invoiceListFormDataLoader = $invoiceListFormDataLoader;
        return $this;
    }
}
