<?php
/**
 * SAM-3905: Auction bidder checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 28, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Validate;

/**
 * Trait AuctionBidderCheckerAwareTrait
 * @package Sam\Bidder\AuctionBidder\Validate
 */
trait AuctionBidderCheckerAwareTrait
{
    protected ?AuctionBidderChecker $auctionBidderChecker = null;

    /**
     * @return AuctionBidderChecker
     */
    protected function getAuctionBidderChecker(): AuctionBidderChecker
    {
        if ($this->auctionBidderChecker === null) {
            $this->auctionBidderChecker = AuctionBidderChecker::new();
        }
        return $this->auctionBidderChecker;
    }

    /**
     * @param AuctionBidderChecker $auctionBidderChecker
     * @return static
     * @internal
     */
    public function setAuctionBidderChecker(AuctionBidderChecker $auctionBidderChecker): static
    {
        $this->auctionBidderChecker = $auctionBidderChecker;
        return $this;
    }
}
