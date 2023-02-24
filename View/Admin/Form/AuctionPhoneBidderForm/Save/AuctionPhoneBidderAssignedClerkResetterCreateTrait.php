<?php
/**
 * SAM-5817: Refactor data loader for Auction Phone Bidder page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Save;


/**
 * Trait AuctionPhoneBidderAssignedClerkResetterCreateTrait
 * @package Sam\View\Admin\Form\AuctionPhoneBidderForm\Save
 */
trait AuctionPhoneBidderAssignedClerkResetterCreateTrait
{
    protected ?AuctionPhoneBidderAssignedClerkResetter $auctionPhoneBidderAssignedClerkResetter = null;

    /**
     * @return AuctionPhoneBidderAssignedClerkResetter
     */
    protected function createAuctionPhoneBidderAssignedClerkResetter(): AuctionPhoneBidderAssignedClerkResetter
    {
        return $this->auctionPhoneBidderAssignedClerkResetter ?: AuctionPhoneBidderAssignedClerkResetter::new();
    }

    /**
     * @param AuctionPhoneBidderAssignedClerkResetter $auctionPhoneBidderAssignedClerkResetter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionPhoneBidderAssignedClerkResetter(AuctionPhoneBidderAssignedClerkResetter $auctionPhoneBidderAssignedClerkResetter): static
    {
        $this->auctionPhoneBidderAssignedClerkResetter = $auctionPhoneBidderAssignedClerkResetter;
        return $this;
    }
}
