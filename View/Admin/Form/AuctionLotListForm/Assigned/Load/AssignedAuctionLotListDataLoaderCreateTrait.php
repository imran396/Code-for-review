<?php
/**
 * SAM-5657: Refactor data loader for Assigned lot datagrid at Auction Lot List page at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load;

/**
 * Trait AssignedAuctionLotListDataLoaderCreateTrait
 * @package
 */
trait AssignedAuctionLotListDataLoaderCreateTrait
{
    protected ?AssignedAuctionLotListDataLoader $assignedAuctionLotListDataLoader = null;

    /**
     * @return AssignedAuctionLotListDataLoader
     */
    protected function createAssignedAuctionLotListDataLoader(): AssignedAuctionLotListDataLoader
    {
        return $this->assignedAuctionLotListDataLoader ?: AssignedAuctionLotListDataLoader::new();
    }

    /**
     * @param AssignedAuctionLotListDataLoader $assignedAuctionLotListDataLoader
     * @return static
     * @internal
     */
    public function setAssignedAuctionLotListDataLoader(AssignedAuctionLotListDataLoader $assignedAuctionLotListDataLoader): static
    {
        $this->assignedAuctionLotListDataLoader = $assignedAuctionLotListDataLoader;
        return $this;
    }
}
