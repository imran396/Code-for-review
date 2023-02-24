<?php
/**
 * SAM-6765: Add "Refresh" button  to refresh the auction cache on demand
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache;

/**
 * Trait SingleAuctionAllCacheUpdaterCreateTrait
 * @package Sam\Auction\Cache
 */
trait SingleAuctionAllCacheUpdaterCreateTrait
{
    protected ?SingleAuctionAllCacheUpdater $singleAuctionAllCacheUpdater = null;

    /**
     * @return SingleAuctionAllCacheUpdater
     */
    protected function createSingleAuctionAllCacheUpdater(): SingleAuctionAllCacheUpdater
    {
        return $this->singleAuctionAllCacheUpdater ?: SingleAuctionAllCacheUpdater::new();
    }

    /**
     * @param SingleAuctionAllCacheUpdater $singleAuctionAllCacheUpdater
     * @return $this
     * @internal
     */
    public function setSingleAuctionAllCacheUpdater(SingleAuctionAllCacheUpdater $singleAuctionAllCacheUpdater): static
    {
        $this->singleAuctionAllCacheUpdater = $singleAuctionAllCacheUpdater;
        return $this;
    }
}
