<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug. 31, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache;


/**
 * Trait AuctionDetailsDbCacheManagerCreateTrait
 * @package Sam\Auction\Cache
 */
trait AuctionDetailsDbCacheManagerCreateTrait
{
    protected ?AuctionDetailsDbCacheManager $auctionDetailsDbCacheManager = null;

    /**
     * @return AuctionDetailsDbCacheManager
     */
    protected function createAuctionDetailsDbCacheManager(): AuctionDetailsDbCacheManager
    {
        return $this->auctionDetailsDbCacheManager ?: AuctionDetailsDbCacheManager::new();
    }

    /**
     * @param AuctionDetailsDbCacheManager $auctionDetailsDbCacheManager
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionDetailsDbCacheManager(AuctionDetailsDbCacheManager $auctionDetailsDbCacheManager): static
    {
        $this->auctionDetailsDbCacheManager = $auctionDetailsDbCacheManager;
        return $this;
    }
}
