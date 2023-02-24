<?php
/**
 * Auction Sort by Consignor Updater Create Trait
 *
 * SAM-5663: Extract update action for Auction Sort by Consignor page at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 06, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSortByConsignorForm\Save;

/**
 * Trait AuctionSortByConsignorUpdaterCreateTrait
 */
trait AuctionSortByConsignorUpdaterCreateTrait
{
    protected ?AuctionSortByConsignorUpdater $auctionSortByConsignorUpdater = null;

    /**
     * @return AuctionSortByConsignorUpdater
     */
    protected function createAuctionSortByConsignorUpdater(): AuctionSortByConsignorUpdater
    {
        $auctionSortByConsignorUpdater = $this->auctionSortByConsignorUpdater
            ?: AuctionSortByConsignorUpdater::new();
        return $auctionSortByConsignorUpdater;
    }

    /**
     * @param AuctionSortByConsignorUpdater $auctionSortByConsignorUpdater
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionSortByConsignorUpdater(AuctionSortByConsignorUpdater $auctionSortByConsignorUpdater): static
    {
        $this->auctionSortByConsignorUpdater = $auctionSortByConsignorUpdater;
        return $this;
    }
}
