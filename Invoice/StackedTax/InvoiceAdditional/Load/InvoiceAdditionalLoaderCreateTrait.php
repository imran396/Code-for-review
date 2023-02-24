<?php
/**
 * SAM-11260: Stacked Tax. Invoice Management pages: Implement unit tests
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\InvoiceAdditional\Load;

/**
 * Trait InvoiceAdditionalLoaderCreateTrait
 * @package Sam\Invoice\StackedTax\InvoiceAdditional\Load
 */
trait InvoiceAdditionalLoaderCreateTrait
{
    protected ?InvoiceAdditionalLoader $invoiceAdditionalLoader = null;

    /**
     * @return InvoiceAdditionalLoader
     */
    protected function createInvoiceAdditionalLoader(): InvoiceAdditionalLoader
    {
        return $this->invoiceAdditionalLoader ?: InvoiceAdditionalLoader::new();
    }

    /**
     * @param InvoiceAdditionalLoader $invoiceAdditionalLoader
     * @return static
     * @internal
     */
    public function setInvoiceAdditionalLoader(InvoiceAdditionalLoader $invoiceAdditionalLoader): static
    {
        $this->invoiceAdditionalLoader = $invoiceAdditionalLoader;
        return $this;
    }
}
