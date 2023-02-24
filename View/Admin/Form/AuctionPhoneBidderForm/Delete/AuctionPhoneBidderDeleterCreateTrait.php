<?php
/**
 * Auction Phone Bidder Deleter Create Trait
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

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Delete;

/**
 * Trait AuctionPhoneBidderDeleterCreateTrait
 */
trait AuctionPhoneBidderDeleterCreateTrait
{
    protected ?AuctionPhoneBidderDeleter $auctionPhoneBidderDeleter = null;

    /**
     * @return AuctionPhoneBidderDeleter
     */
    protected function createAuctionPhoneBidderDeleter(): AuctionPhoneBidderDeleter
    {
        $auctionPhoneBidderDeleter = $this->auctionPhoneBidderDeleter ?: AuctionPhoneBidderDeleter::new();
        return $auctionPhoneBidderDeleter;
    }

    /**
     * @param AuctionPhoneBidderDeleter $auctionPhoneBidderDeleter
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionPhoneBidderDeleter(AuctionPhoneBidderDeleter $auctionPhoneBidderDeleter): static
    {
        $this->auctionPhoneBidderDeleter = $auctionPhoneBidderDeleter;
        return $this;
    }
}
