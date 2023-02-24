<?php
/**
 * Auction Phone Bidder Updater Create Trait
 *
 * SAM-5817: Refactor data loader for Auction Phone Bidder page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Save;

/**
 * Trait AuctionPhoneBidderUpdaterCreateTrait
 */
trait AuctionPhoneBidderUpdaterCreateTrait
{
    protected ?AuctionPhoneBidderUpdater $auctionPhoneBidderUpdater = null;

    /**
     * @return AuctionPhoneBidderUpdater
     */
    protected function createAuctionPhoneBidderUpdater(): AuctionPhoneBidderUpdater
    {
        $auctionPhoneBidderUpdater = $this->auctionPhoneBidderUpdater ?: AuctionPhoneBidderUpdater::new();
        return $auctionPhoneBidderUpdater;
    }

    /**
     * @param AuctionPhoneBidderUpdater $auctionPhoneBidderUpdater
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionPhoneBidderUpdater(AuctionPhoneBidderUpdater $auctionPhoneBidderUpdater): static
    {
        $this->auctionPhoneBidderUpdater = $auctionPhoneBidderUpdater;
        return $this;
    }
}
