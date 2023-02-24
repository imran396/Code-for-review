<?php
/**
 * SAM-6041: Extract auction cache modified timestamp drop to separate class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           4/30/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\CacheInvalidator;

/**
 * Trait AuctionCacheInvalidatorCreateTrait
 * @package Sam\Auction\Cache
 */
trait AuctionCacheInvalidatorCreateTrait
{
    protected ?AuctionCacheInvalidator $auctionCacheInvalidator = null;

    /**
     * @return AuctionCacheInvalidator
     */
    protected function createAuctionCacheInvalidator(): AuctionCacheInvalidator
    {
        return $this->auctionCacheInvalidator ?: AuctionCacheInvalidator::new();
    }

    /**
     * @param AuctionCacheInvalidator $auctionCacheInvalidator
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionCacheInvalidator(AuctionCacheInvalidator $auctionCacheInvalidator): static
    {
        $this->auctionCacheInvalidator = $auctionCacheInvalidator;
        return $this;
    }
}
