<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/10/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache;

/**
 * Trait AuctionDbCacheManagerAwareTrait
 * @package Sam\Auction\Cache
 */
trait AuctionDbCacheManagerAwareTrait
{
    protected ?AuctionDbCacheManager $auctionDbCacheManager = null;

    /**
     * @return AuctionDbCacheManager
     */
    protected function getAuctionDbCacheManager(): AuctionDbCacheManager
    {
        if ($this->auctionDbCacheManager === null) {
            $this->auctionDbCacheManager = AuctionDbCacheManager::new();
        }
        return $this->auctionDbCacheManager;
    }

    /**
     * @param AuctionDbCacheManager $auctionDbCacheManager
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionDbCacheManager(AuctionDbCacheManager $auctionDbCacheManager): static
    {
        $this->auctionDbCacheManager = $auctionDbCacheManager;
        return $this;
    }
}
