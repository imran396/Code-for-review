<?php

/**
 * Auction Bidder Unassigned User Data Loader Create Trait
 *
 * SAM-5593: Refactor data loaders for Auction Bidder List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderForm\Unassigned\Load;

/**
 * Trait AuctionBidderDataLoaderCreateTrait
 */
trait AuctionBidderUnassignedUserDataLoaderCreateTrait
{
    protected ?AuctionBidderUnassignedUserDataLoader $auctionBidderUnassignedUserDataLoader = null;

    /**
     * @return AuctionBidderUnassignedUserDataLoader
     */
    protected function createAuctionBidderUnassignedUserDataLoader(): AuctionBidderUnassignedUserDataLoader
    {
        $auctionBidderUnassignedUserDataLoader = $this->auctionBidderUnassignedUserDataLoader
            ?: AuctionBidderUnassignedUserDataLoader::new();
        return $auctionBidderUnassignedUserDataLoader;
    }

    /**
     * @param AuctionBidderUnassignedUserDataLoader $auctionBidderUnassignedUserDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionBidderUnassignedUserDataLoader(AuctionBidderUnassignedUserDataLoader $auctionBidderUnassignedUserDataLoader): static
    {
        $this->auctionBidderUnassignedUserDataLoader = $auctionBidderUnassignedUserDataLoader;
        return $this;
    }
}
