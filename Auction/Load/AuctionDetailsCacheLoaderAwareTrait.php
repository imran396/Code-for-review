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

namespace Sam\Auction\Load;


/**
 * Trait AuctionDetailsCacheLoaderAwareTrait
 * @package Sam\Auction\Load
 */
trait AuctionDetailsCacheLoaderAwareTrait
{
    protected ?AuctionDetailsCacheLoader $auctionDetailsCacheLoader = null;

    /**
     * @return AuctionDetailsCacheLoader
     */
    protected function getAuctionDetailsCacheLoader(): AuctionDetailsCacheLoader
    {
        if ($this->auctionDetailsCacheLoader === null) {
            $this->auctionDetailsCacheLoader = AuctionDetailsCacheLoader::new();
        }
        return $this->auctionDetailsCacheLoader;
    }

    /**
     * @param AuctionDetailsCacheLoader $auctionDetailsCacheLoader
     * @return static
     * @internal
     */
    public function setAuctionDetailsCacheLoader(AuctionDetailsCacheLoader $auctionDetailsCacheLoader): static
    {
        $this->auctionDetailsCacheLoader = $auctionDetailsCacheLoader;
        return $this;
    }
}
