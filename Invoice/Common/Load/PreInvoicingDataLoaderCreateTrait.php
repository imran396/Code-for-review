<?php
/**
 * Loads data required for pre-invoice generation, un-invoiced users, auctions, lots
 *
 * SAM-4668: Pre-invoicing data loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/1/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load;

/**
 * Trait PreInvoicingDataLoaderAwareTrait
 * @package Sam\Invoice\Common\Load
 */
trait PreInvoicingDataLoaderCreateTrait
{
    /**
     * @var PreInvoicingDataLoader|null
     */
    protected ?PreInvoicingDataLoader $preInvoicingDataLoader = null;

    /**
     * @return PreInvoicingDataLoader
     */
    protected function createPreInvoicingDataLoader(): PreInvoicingDataLoader
    {
        return $this->preInvoicingDataLoader ?: PreInvoicingDataLoader::new();
    }

    /**
     * @param PreInvoicingDataLoader $preInvoicingDataLoader
     * @return static
     * @internal
     */
    public function setPreInvoicingDataLoader(PreInvoicingDataLoader $preInvoicingDataLoader): static
    {
        $this->preInvoicingDataLoader = $preInvoicingDataLoader;
        return $this;
    }
}
