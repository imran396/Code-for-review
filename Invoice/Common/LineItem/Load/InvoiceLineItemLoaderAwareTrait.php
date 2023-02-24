<?php
/**
 * SAM-4723: Invoice Line item editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/22/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Load;

/**
 * Trait InvoiceLineItemLoaderAwareTrait
 * @package Sam\Invoice\Common\LineItem\Load
 */
trait InvoiceLineItemLoaderAwareTrait
{
    /**
     * @var InvoiceLineItemLoader|null
     */
    protected ?InvoiceLineItemLoader $invoiceLineItemLoader = null;

    /**
     * @return InvoiceLineItemLoader
     */
    protected function getInvoiceLineItemLoader(): InvoiceLineItemLoader
    {
        if ($this->invoiceLineItemLoader === null) {
            $this->invoiceLineItemLoader = InvoiceLineItemLoader::new();
        }
        return $this->invoiceLineItemLoader;
    }

    /**
     * @param InvoiceLineItemLoader $invoiceLineItemLoader
     * @return static
     * @internal
     */
    public function setInvoiceLineItemLoader(InvoiceLineItemLoader $invoiceLineItemLoader): static
    {
        $this->invoiceLineItemLoader = $invoiceLineItemLoader;
        return $this;
    }
}
