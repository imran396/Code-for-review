<?php
/**
 *
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load;

/**
 * Trait InvoiceAuctionLoaderCreateTrait
 * @package Sam\Invoice\Common\Load
 */
trait InvoiceAuctionLoaderCreateTrait
{
    /**
     * @var InvoiceAuctionLoader|null
     */
    protected ?InvoiceAuctionLoader $invoiceAuctionLoader = null;

    /**
     * @return InvoiceAuctionLoader
     */
    protected function createInvoiceAuctionLoader(): InvoiceAuctionLoader
    {
        return $this->invoiceAuctionLoader ?: InvoiceAuctionLoader::new();
    }

    /**
     * @param InvoiceAuctionLoader $invoiceAuctionLoader
     * @return $this
     * @internal
     */
    public function setInvoiceAuctionLoader(InvoiceAuctionLoader $invoiceAuctionLoader): static
    {
        $this->invoiceAuctionLoader = $invoiceAuctionLoader;
        return $this;
    }
}
