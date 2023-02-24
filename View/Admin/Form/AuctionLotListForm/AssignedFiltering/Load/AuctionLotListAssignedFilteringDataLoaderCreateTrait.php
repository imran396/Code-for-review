<?php
/**
 * Auction Lot List Assigned Filtering Data Loader Create Trait
 * SAM-6562: Move data loading for Winning Bidder of Added Lots filtering at Auction Lot List page of admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\AssignedFiltering\Load;


/**
 * Trait AuctionLotListAssignedFilteringDataLoaderCreateTrait
 */
trait AuctionLotListAssignedFilteringDataLoaderCreateTrait
{
    protected ?AuctionLotListAssignedFilteringDataLoader $auctionLotListAssignedFilteringDataLoader = null;

    /**
     * @return AuctionLotListAssignedFilteringDataLoader
     */
    protected function createAuctionLotListAssignedFilteringDataLoader(): AuctionLotListAssignedFilteringDataLoader
    {
        return $this->auctionLotListAssignedFilteringDataLoader ?: AuctionLotListAssignedFilteringDataLoader::new();
    }

    /**
     * @param AuctionLotListAssignedFilteringDataLoader $auctionLotListAssignedFilteringDataLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionLotListAssignedFilteringDataLoader(AuctionLotListAssignedFilteringDataLoader $auctionLotListAssignedFilteringDataLoader): static
    {
        $this->auctionLotListAssignedFilteringDataLoader = $auctionLotListAssignedFilteringDataLoader;
        return $this;
    }
}
