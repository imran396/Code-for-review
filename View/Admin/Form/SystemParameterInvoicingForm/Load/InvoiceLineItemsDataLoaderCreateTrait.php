<?php
/**
 * Invoice Line Items Data Loader Create Trait
 *
 * SAM-6442: Refactor system parameters invoicing and payment page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterInvoicingForm\Load;


/**
 * Trait InvoiceLineItemsDataLoaderCreateTrait
 */
trait InvoiceLineItemsDataLoaderCreateTrait
{
    protected ?InvoiceLineItemsDataLoader $invoiceLineItemsDataLoader = null;

    /**
     * @return InvoiceLineItemsDataLoader
     */
    protected function createInvoiceLineItemsDataLoader(): InvoiceLineItemsDataLoader
    {
        return $this->invoiceLineItemsDataLoader ?: InvoiceLineItemsDataLoader::new();
    }

    /**
     * @param InvoiceLineItemsDataLoader $invoiceLineItemsDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setInvoiceLineItemsDataLoader(InvoiceLineItemsDataLoader $invoiceLineItemsDataLoader): static
    {
        $this->invoiceLineItemsDataLoader = $invoiceLineItemsDataLoader;
        return $this;
    }
}
