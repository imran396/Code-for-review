<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\CacheInvalidator;


/**
 * Trait AuctionDetailsCacheInvalidatorCreateTrait
 * @package Sam\Auction\Cache\CacheInvalidator
 */
trait AuctionDetailsCacheInvalidatorCreateTrait
{
    protected ?AuctionDetailsCacheInvalidator $auctionDetailsCacheInvalidator = null;

    /**
     * @return AuctionDetailsCacheInvalidator
     */
    protected function createAuctionDetailsCacheInvalidator(): AuctionDetailsCacheInvalidator
    {
        return $this->auctionDetailsCacheInvalidator ?: AuctionDetailsCacheInvalidator::new();
    }

    /**
     * @param AuctionDetailsCacheInvalidator $auctionDetailsCacheInvalidator
     * @return static
     * @internal
     */
    public function setAuctionDetailsCacheInvalidator(AuctionDetailsCacheInvalidator $auctionDetailsCacheInvalidator): static
    {
        $this->auctionDetailsCacheInvalidator = $auctionDetailsCacheInvalidator;
        return $this;
    }
}
