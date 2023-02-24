<?php
/**
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 * SAM-5400: Rtb state update refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Bidder\Manage;

/**
 * Trait CatalogServiceFactoryCreateTrait
 * @package Sam\Rtb\Catalog
 */
trait BidderCatalogManagerFactoryCreateTrait
{
    protected ?BidderCatalogManagerFactory $bidderCatalogManagerFactory = null;

    /**
     * @return BidderCatalogManagerFactory
     */
    protected function createCatalogManagerFactory(): BidderCatalogManagerFactory
    {
        return $this->bidderCatalogManagerFactory ?: BidderCatalogManagerFactory::new();
    }

    /**
     * @param BidderCatalogManagerFactory $bidderCatalogManagerFactory
     * @return static
     * @internal
     */
    public function setBidderCatalogManagerFactory(BidderCatalogManagerFactory $bidderCatalogManagerFactory): static
    {
        $this->bidderCatalogManagerFactory = $bidderCatalogManagerFactory;
        return $this;
    }
}
