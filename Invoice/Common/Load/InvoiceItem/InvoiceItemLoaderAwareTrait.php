<?php
/**
 * Trait for Invoice Renderer
 *
 * SAM-4334:Optimize data loading for invoice pages https://bidpath.atlassian.net/browse/SAM-4334
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load\InvoiceItem;

/**
 * Trait InvoiceItemLoaderAwareTrait
 * @package Sam\Invoice\Common\Load
 */
trait InvoiceItemLoaderAwareTrait
{
    protected ?InvoiceItemLoader $invoiceItemLoader = null;

    /**
     * @param InvoiceItemLoader $invoiceItemLoader
     * @return static
     * @internal
     */
    public function setInvoiceItemLoader(InvoiceItemLoader $invoiceItemLoader): static
    {
        $this->invoiceItemLoader = $invoiceItemLoader;
        return $this;
    }

    /**
     * @return InvoiceItemLoader
     */
    protected function getInvoiceItemLoader(): InvoiceItemLoader
    {
        if ($this->invoiceItemLoader === null) {
            $this->invoiceItemLoader = InvoiceItemLoader::new();
        }
        return $this->invoiceItemLoader;
    }
}
