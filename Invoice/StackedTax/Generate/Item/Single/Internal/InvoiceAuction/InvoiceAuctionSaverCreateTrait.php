<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceAuction;

trait InvoiceAuctionSaverCreateTrait
{
    protected ?InvoiceAuctionSaver $invoiceAuctionSaver = null;

    /**
     * @return InvoiceAuctionSaver
     */
    protected function createInvoiceAuctionSaver(): InvoiceAuctionSaver
    {
        return $this->invoiceAuctionSaver ?: InvoiceAuctionSaver::new();
    }

    /**
     * @param InvoiceAuctionSaver $invoiceAuctionSaver
     * @return $this
     * @internal
     */
    public function setInvoiceAuctionSaver(InvoiceAuctionSaver $invoiceAuctionSaver): static
    {
        $this->invoiceAuctionSaver = $invoiceAuctionSaver;
        return $this;
    }
}
