<?php

/**
 * Auction Bidder Overview Data Loader Create Trait
 *
 * SAM-5600: Refactor data loader for Auction Bidder Overview page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderOverviewForm\Load;

/**
 * Trait AuctionBidderOverviewDataLoaderCreateTrait
 */
trait AuctionBidderOverviewDataLoaderCreateTrait
{
    protected ?AuctionBidderOverviewDataLoader $auctionBidderOverviewDataLoader = null;

    /**
     * @return AuctionBidderOverviewDataLoader
     */
    protected function createAuctionBidderOverviewDataLoader(): AuctionBidderOverviewDataLoader
    {
        $auctionBidderOverviewDataLoader = $this->auctionBidderOverviewDataLoader
            ?: AuctionBidderOverviewDataLoader::new();
        return $auctionBidderOverviewDataLoader;
    }

    /**
     * @param AuctionBidderOverviewDataLoader $auctionBidderOverviewDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionBidderOverviewDataLoader(AuctionBidderOverviewDataLoader $auctionBidderOverviewDataLoader): static
    {
        $this->auctionBidderOverviewDataLoader = $auctionBidderOverviewDataLoader;
        return $this;
    }
}
