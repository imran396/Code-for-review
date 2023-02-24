<?php
/**
 * SAM-6755: Programmatic fix to prevent user from being registered for the same auction twice
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Save;

/**
 * Trait AuctionBidderDBLockerCreateTrait
 * @package Sam\Bidder\AuctionBidder\Save
 */
trait AuctionBidderDbLockerCreateTrait
{
    protected ?AuctionBidderDbLocker $auctionBidderDBLocker = null;

    /**
     * @return AuctionBidderDbLocker
     */
    protected function createAuctionBidderDbLocker(): AuctionBidderDbLocker
    {
        return $this->auctionBidderDBLocker ?: AuctionBidderDbLocker::new();
    }

    /**
     * @param AuctionBidderDbLocker $auctionBidderDBLocker
     * @return static
     * @internal
     */
    public function setAuctionBidderDbLocker(AuctionBidderDbLocker $auctionBidderDBLocker): static
    {
        $this->auctionBidderDBLocker = $auctionBidderDBLocker;
        return $this;
    }
}
