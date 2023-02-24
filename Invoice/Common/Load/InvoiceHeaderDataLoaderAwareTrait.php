<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Load;

/**
 * Trait InvoiceHeaderDataLoaderAwareTrait
 * @package Sam\Invoice\Common\Load
 */
trait InvoiceHeaderDataLoaderAwareTrait
{
    /**
     * @var InvoiceHeaderDataLoader|null
     */
    protected ?InvoiceHeaderDataLoader $invoiceHeaderDataLoader = null;

    /**
     * @return InvoiceHeaderDataLoader
     */
    protected function getInvoiceHeaderDataLoader(): InvoiceHeaderDataLoader
    {
        if ($this->invoiceHeaderDataLoader === null) {
            $this->invoiceHeaderDataLoader = InvoiceHeaderDataLoader::new();
        }
        return $this->invoiceHeaderDataLoader;
    }

    /**
     * @param InvoiceHeaderDataLoader $invoiceHeaderDataLoader
     * @return static
     * @internal
     */
    public function setInvoiceHeaderDataLoader(InvoiceHeaderDataLoader $invoiceHeaderDataLoader): static
    {
        $this->invoiceHeaderDataLoader = $invoiceHeaderDataLoader;
        return $this;
    }
}
