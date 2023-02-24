<?php
/**
 * Trait for Invoice Loader
 *
 * SAM-4337: Invoice Loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 09, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load;

/**
 * Trait InvoiceLoaderAwareTrait
 * @package Sam\Invoice\Common\Load
 */
trait InvoiceLoaderAwareTrait
{
    /**
     * @var InvoiceLoader|null
     */
    protected ?InvoiceLoader $invoiceLoader = null;

    /**
     * @return InvoiceLoader
     */
    protected function getInvoiceLoader(): InvoiceLoader
    {
        if ($this->invoiceLoader === null) {
            $this->invoiceLoader = InvoiceLoader::new();
        }
        return $this->invoiceLoader;
    }

    /**
     * @param InvoiceLoader $invoiceLoader
     * @return static
     */
    public function setInvoiceLoader(InvoiceLoader $invoiceLoader): static
    {
        $this->invoiceLoader = $invoiceLoader;
        return $this;
    }
}
