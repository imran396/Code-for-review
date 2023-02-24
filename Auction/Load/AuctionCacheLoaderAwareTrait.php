<?php
/**
 * Trait for Auction Cache Loader
 *
 * SAM-4102: AuctionCache Loader class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 13, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Load;

/**
 * Trait AuctionCacheLoaderAwareTrait
 * @package Sam\Auction\Load
 */
trait AuctionCacheLoaderAwareTrait
{
    protected ?AuctionCacheLoader $auctionCacheLoader = null;

    /**
     * @return AuctionCacheLoader
     */
    protected function getAuctionCacheLoader(): AuctionCacheLoader
    {
        if ($this->auctionCacheLoader === null) {
            $this->auctionCacheLoader = AuctionCacheLoader::new();
        }
        return $this->auctionCacheLoader;
    }

    /**
     * @param AuctionCacheLoader $auctionCacheLoader
     * @return static
     * @internal
     */
    public function setAuctionCacheLoader(AuctionCacheLoader $auctionCacheLoader): static
    {
        $this->auctionCacheLoader = $auctionCacheLoader;
        return $this;
    }
}
