<?php
/**
 * SAM-10106: Supply lot winning info correspondence for winning auction and winning bidder fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\WinningBidder;

/**
 * Trait WinningBidderAuctionCheckerCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait WinningBidderAuctionCheckerCreateTrait
{
    /**
     * @var WinningBidderAuctionChecker|null
     */
    protected ?WinningBidderAuctionChecker $winningBidderAuctionChecker = null;

    /**
     * @return WinningBidderAuctionChecker
     */
    protected function createWinningBidderAuctionChecker(): WinningBidderAuctionChecker
    {
        return $this->winningBidderAuctionChecker ?: WinningBidderAuctionChecker::new();
    }

    /**
     * @param WinningBidderAuctionChecker $winningBidderAuctionChecker
     * @return static
     * @internal
     */
    public function setWinningBidderAuctionChecker(WinningBidderAuctionChecker $winningBidderAuctionChecker): static
    {
        $this->winningBidderAuctionChecker = $winningBidderAuctionChecker;
        return $this;
    }
}
