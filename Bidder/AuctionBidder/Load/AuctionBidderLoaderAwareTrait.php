<?php
/**
 * SAM-5338: Auction bidder loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Load;

/**
 * Trait AuctionBidderLoaderAwareTrait
 * @package Sam\Bidder\AuctionBidder\Load
 */
trait AuctionBidderLoaderAwareTrait
{
    protected ?AuctionBidderLoader $auctionBidderLoader = null;

    /**
     * @return AuctionBidderLoader
     */
    protected function getAuctionBidderLoader(): AuctionBidderLoader
    {
        if ($this->auctionBidderLoader === null) {
            $this->auctionBidderLoader = AuctionBidderLoader::new();
        }
        return $this->auctionBidderLoader;
    }

    /**
     * @param AuctionBidderLoader $auctionBidderLoader
     * @return static
     * @internal
     */
    public function setAuctionBidderLoader(AuctionBidderLoader $auctionBidderLoader): static
    {
        $this->auctionBidderLoader = $auctionBidderLoader;
        return $this;
    }
}
