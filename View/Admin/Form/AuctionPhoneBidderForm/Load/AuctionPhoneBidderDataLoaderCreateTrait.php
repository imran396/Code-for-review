<?php
/**
 * Auction Phone Bidder Data Loader Create Trait
 *
 * SAM-5817: Refactor data loader for Auction Phone Bidder page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 18, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Load;

/**
 * Trait AuctionPhoneBidderDataLoaderCreateTrait
 */
trait AuctionPhoneBidderDataLoaderCreateTrait
{
    protected ?AuctionPhoneBidderDataLoader $auctionPhoneBidderDataLoader = null;

    /**
     * @return AuctionPhoneBidderDataLoader
     */
    protected function createAuctionPhoneBidderDataLoader(): AuctionPhoneBidderDataLoader
    {
        $auctionPhoneBidderDataLoader = $this->auctionPhoneBidderDataLoader
            ?: AuctionPhoneBidderDataLoader::new();
        return $auctionPhoneBidderDataLoader;
    }

    /**
     * @param AuctionPhoneBidderDataLoader $auctionPhoneBidderDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionPhoneBidderDataLoader(AuctionPhoneBidderDataLoader $auctionPhoneBidderDataLoader): static
    {
        $this->auctionPhoneBidderDataLoader = $auctionPhoneBidderDataLoader;
        return $this;
    }
}
