<?php
/**
 * Auction Bidder Assigned User Data Loader Create Trait
 *
 * SAM-5593: Refactor data loaders for Auction Bidder List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 24, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderForm\Assigned\Load;

/**
 * Trait AuctionBidderAssignedUserDataLoaderCreateTrait
 */
trait AuctionBidderAssignedUserDataLoaderCreateTrait
{
    protected ?AuctionBidderAssignedUserDataLoader $auctionBidderAssignedUserDataLoader = null;

    /**
     * @return AuctionBidderAssignedUserDataLoader
     */
    protected function createAuctionBidderAssignedUserDataLoader(): AuctionBidderAssignedUserDataLoader
    {
        $auctionBidderAssignedUserDataLoader = $this->auctionBidderAssignedUserDataLoader
            ?: AuctionBidderAssignedUserDataLoader::new();
        return $auctionBidderAssignedUserDataLoader;
    }

    /**
     * @param AuctionBidderAssignedUserDataLoader $auctionBidderAssignedUserDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionBidderAssignedUserDataLoader(AuctionBidderAssignedUserDataLoader $auctionBidderAssignedUserDataLoader): static
    {
        $this->auctionBidderAssignedUserDataLoader = $auctionBidderAssignedUserDataLoader;
        return $this;
    }
}
