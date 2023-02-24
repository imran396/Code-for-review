<?php
/**
 * SAM-10997: Stacked Tax. New Invoice Edit page: Goods section (Invoice Items)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\Common\Goods\Load;

/**
 * Trait InvoiceItemDataLoaderCreateTrait
 * @package Sam\Invoice\StackedTax\View\Common\Goods\Load
 */
trait InvoiceItemDataLoaderCreateTrait
{
    protected ?InvoiceItemDataLoader $invoiceItemDataLoader = null;

    /**
     * @return InvoiceItemDataLoader
     */
    protected function createInvoiceItemDataLoader(): InvoiceItemDataLoader
    {
        return $this->invoiceItemDataLoader ?: InvoiceItemDataLoader::new();
    }

    /**
     * @param InvoiceItemDataLoader $invoiceItemDataLoader
     * @return static
     * @internal
     */
    public function setInvoiceItemDataLoader(InvoiceItemDataLoader $invoiceItemDataLoader): static
    {
        $this->invoiceItemDataLoader = $invoiceItemDataLoader;
        return $this;
    }
}
