<?php
/**
 * Auction Phone Bidder Auction Updater Create Trait
 *
 * SAM-5817: Refactor data loader for Auction Phone Bidder page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Save;

/**
 * Trait AuctionPhoneBidderAuctionUpdaterCreateTrait
 */
trait AuctionPhoneBidderAuctionUpdaterCreateTrait
{
    protected ?AuctionPhoneBidderAuctionUpdater $auctionPhoneBidderAuctionUpdater = null;

    /**
     * @return AuctionPhoneBidderAuctionUpdater
     */
    protected function createAuctionPhoneBidderAuctionUpdater(): AuctionPhoneBidderAuctionUpdater
    {
        $auctionPhoneBidderAuctionUpdater = $this->auctionPhoneBidderAuctionUpdater
            ?: AuctionPhoneBidderAuctionUpdater::new();
        return $auctionPhoneBidderAuctionUpdater;
    }

    /**
     * @param AuctionPhoneBidderAuctionUpdater $auctionPhoneBidderAuctionUpdater
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionPhoneBidderAuctionUpdater(
        AuctionPhoneBidderAuctionUpdater $auctionPhoneBidderAuctionUpdater
    ): static {
        $this->auctionPhoneBidderAuctionUpdater = $auctionPhoneBidderAuctionUpdater;
        return $this;
    }
}
