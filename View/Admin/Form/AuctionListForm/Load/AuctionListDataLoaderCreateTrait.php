<?php

/**
 * Auction List Data Loader Create Trait
 *
 * SAM-5584: Refactor data loader for Auction List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 28, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionListForm\Load;

/**
 * Trait AuctionListDataLoaderCreateTrait
 */
trait AuctionListDataLoaderCreateTrait
{
    protected ?AuctionListDataLoader $auctionListDataLoader = null;

    /**
     * @return AuctionListDataLoader
     */
    protected function createAuctionListDataLoader(): AuctionListDataLoader
    {
        $auctionListDataLoader = $this->auctionListDataLoader ?: AuctionListDataLoader::new();
        return $auctionListDataLoader;
    }

    /**
     * @param AuctionListDataLoader $auctionListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionListDataLoader(AuctionListDataLoader $auctionListDataLoader): static
    {
        $this->auctionListDataLoader = $auctionListDataLoader;
        return $this;
    }
}
