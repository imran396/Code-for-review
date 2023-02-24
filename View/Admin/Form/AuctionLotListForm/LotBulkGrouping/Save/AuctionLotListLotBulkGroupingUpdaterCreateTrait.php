<?php
/**
 * SAM-6627: Extract "Add to Bulk" updating functionality from Admin Auction Lot List page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotBulkGrouping\Save;

/**
 * Trait AuctionLotListLotBulkGroupingUpdaterCreateTrait
 * @package
 */
trait AuctionLotListLotBulkGroupingUpdaterCreateTrait
{
    protected ?AuctionLotListLotBulkGroupingUpdater $auctionLotListLotBulkGroupingUpdater = null;

    /**
     * @return AuctionLotListLotBulkGroupingUpdater
     */
    protected function createAuctionLotListLotBulkGroupingUpdater(): AuctionLotListLotBulkGroupingUpdater
    {
        return $this->auctionLotListLotBulkGroupingUpdater ?: AuctionLotListLotBulkGroupingUpdater::new();
    }

    /**
     * @param AuctionLotListLotBulkGroupingUpdater $auctionLotListLotBulkGroupingUpdater
     * @return $this
     * @internal
     */
    public function setAuctionLotListLotBulkGroupingUpdater(AuctionLotListLotBulkGroupingUpdater $auctionLotListLotBulkGroupingUpdater): static
    {
        $this->auctionLotListLotBulkGroupingUpdater = $auctionLotListLotBulkGroupingUpdater;
        return $this;
    }
}
