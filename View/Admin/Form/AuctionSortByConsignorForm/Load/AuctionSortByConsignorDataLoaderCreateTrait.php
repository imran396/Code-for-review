<?php
/**
 * Auction Sort by Consignor Data Loader Create Trait
 *
 * SAM-5587: Refactor data loader for Auction Sort by Consignor page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 06, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSortByConsignorForm\Load;

/**
 * Trait AuctionSortByConsignorDataLoaderCreateTrait
 */
trait AuctionSortByConsignorDataLoaderCreateTrait
{
    protected ?AuctionSortByConsignorDataLoader $auctionSortByConsignorDataLoader = null;

    /**
     * @return AuctionSortByConsignorDataLoader
     */
    protected function createAuctionSortByConsignorDataLoader(): AuctionSortByConsignorDataLoader
    {
        $auctionSortByConsignorDataLoader = $this->auctionSortByConsignorDataLoader
            ?: AuctionSortByConsignorDataLoader::new();
        return $auctionSortByConsignorDataLoader;
    }

    /**
     * @param AuctionSortByConsignorDataLoader $auctionSortByConsignorDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionSortByConsignorDataLoader(AuctionSortByConsignorDataLoader $auctionSortByConsignorDataLoader): static
    {
        $this->auctionSortByConsignorDataLoader = $auctionSortByConsignorDataLoader;
        return $this;
    }
}
