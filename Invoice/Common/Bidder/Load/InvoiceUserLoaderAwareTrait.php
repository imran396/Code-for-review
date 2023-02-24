<?php
/**
 *
 * SAM-4554: Move Invoice_Bidder logic to InvoiceUserLoader, InvoiceUserProducer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/13/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Bidder\Load;

/**
 * Trait InvoiceUserLoaderAwareTrait
 * @package Sam\Invoice\Common\Bidder\Load
 */
trait InvoiceUserLoaderAwareTrait
{
    /**
     * @var InvoiceUserLoader|null
     */
    protected ?InvoiceUserLoader $invoiceUserLoader = null;

    /**
     * @return InvoiceUserLoader
     */
    protected function getInvoiceUserLoader(): InvoiceUserLoader
    {
        if ($this->invoiceUserLoader === null) {
            $this->invoiceUserLoader = InvoiceUserLoader::new();
        }
        return $this->invoiceUserLoader;
    }

    /**
     * @param InvoiceUserLoader $invoiceUserLoader
     * @return static
     * @internal
     */
    public function setInvoiceUserLoader(InvoiceUserLoader $invoiceUserLoader): static
    {
        $this->invoiceUserLoader = $invoiceUserLoader;
        return $this;
    }
}
