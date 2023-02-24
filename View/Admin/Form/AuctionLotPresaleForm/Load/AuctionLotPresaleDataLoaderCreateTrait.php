<?php
/**
 * Auction Lot Presale Data Loader Create Trait
 *
 * SAM-5586: Refactor data loader for Auction Lot Presale page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 29, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotPresaleForm\Load;

/**
 * Trait AuctionLotPresaleDataLoaderCreateTrait
 */
trait AuctionLotPresaleDataLoaderCreateTrait
{
    protected ?AuctionLotPresaleDataLoader $auctionLotPresaleDataLoader = null;

    /**
     * @return AuctionLotPresaleDataLoader
     */
    protected function createAuctionLotPresaleDataLoader(): AuctionLotPresaleDataLoader
    {
        $auctionLotPresaleDataLoader = $this->auctionLotPresaleDataLoader ?: AuctionLotPresaleDataLoader::new();
        return $auctionLotPresaleDataLoader;
    }

    /**
     * @param AuctionLotPresaleDataLoader $auctionLotPresaleDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionLotPresaleDataLoader(AuctionLotPresaleDataLoader $auctionLotPresaleDataLoader): static
    {
        $this->auctionLotPresaleDataLoader = $auctionLotPresaleDataLoader;
        return $this;
    }
}
