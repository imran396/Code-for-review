<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Load;

/**
 * Trait AuctionBidderOptionLoaderCreateTrait
 * @package Sam\Bidder\AuctionBidder\Load
 */
trait AuctionBidderOptionLoaderCreateTrait
{
    protected ?AuctionBidderOptionLoader $auctionBidderOptionLoader = null;

    /**
     * @return AuctionBidderOptionLoader
     */
    protected function createAuctionBidderOptionLoader(): AuctionBidderOptionLoader
    {
        return $this->auctionBidderOptionLoader ?: AuctionBidderOptionLoader::new();
    }

    /**
     * @param AuctionBidderOptionLoader $loader
     * @return static
     * @internal
     */
    public function setAuctionBidderOptionLoader(AuctionBidderOptionLoader $loader): static
    {
        $this->auctionBidderOptionLoader = $loader;
        return $this;
    }
}
