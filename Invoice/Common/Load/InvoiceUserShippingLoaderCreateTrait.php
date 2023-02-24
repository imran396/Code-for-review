<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load;

/**
 * Trait InvoiceUserShippingLoaderCreateTrait
 * @package Sam\Invoice\Common\Load
 */
trait InvoiceUserShippingLoaderCreateTrait
{
    /**
     * @var InvoiceUserShippingLoader|null
     */
    protected ?InvoiceUserShippingLoader $invoiceUserShippingLoader = null;

    /**
     * @return InvoiceUserShippingLoader
     */
    protected function createInvoiceUserShippingLoader(): InvoiceUserShippingLoader
    {
        return $this->invoiceUserShippingLoader ?: InvoiceUserShippingLoader::new();
    }

    /**
     * @param InvoiceUserShippingLoader $loader
     * @return static
     * @internal
     */
    public function setInvoiceUserShippingLoader(InvoiceUserShippingLoader $loader): static
    {
        $this->invoiceUserShippingLoader = $loader;
        return $this;
    }
}
