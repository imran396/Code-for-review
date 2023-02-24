<?php
/**
 * Auction Bidder related checks
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Auction\Bidder;

/**
 * Trait AuctionBidderPureCheckerCreateTrait
 * @package Sam\Core\Auction\Bidder
 */
trait AuctionBidderPureCheckerCreateTrait
{
    protected ?AuctionBidderPureChecker $auctionBidderPureChecker = null;

    /**
     * @return AuctionBidderPureChecker
     */
    protected function createAuctionBidderPureChecker(): AuctionBidderPureChecker
    {
        return $this->auctionBidderPureChecker ?: AuctionBidderPureChecker::new();
    }

    /**
     * @param AuctionBidderPureChecker $auctionBidderPureChecker
     * @return static
     * @internal
     */
    public function setAuctionBidderPureChecker(AuctionBidderPureChecker $auctionBidderPureChecker): static
    {
        $this->auctionBidderPureChecker = $auctionBidderPureChecker;
        return $this;
    }
}
