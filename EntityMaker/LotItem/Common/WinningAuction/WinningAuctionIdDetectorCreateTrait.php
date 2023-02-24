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

namespace Sam\EntityMaker\LotItem\Common\WinningAuction;

/**
 * Trait WinningAuctionIdDetectorCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait WinningAuctionIdDetectorCreateTrait
{
    /**
     * @var WinningAuctionIdDetector|null
     */
    protected ?WinningAuctionIdDetector $winningAuctionIdDetector = null;

    /**
     * @return WinningAuctionIdDetector
     */
    protected function createAuctionSoldIdDetector(): WinningAuctionIdDetector
    {
        return $this->winningAuctionIdDetector ?: WinningAuctionIdDetector::new();
    }

    /**
     * @param WinningAuctionIdDetector $winningAuctionIdDetector
     * @return static
     * @internal
     */
    public function setWinningAuctionIdDetector(WinningAuctionIdDetector $winningAuctionIdDetector): static
    {
        $this->winningAuctionIdDetector = $winningAuctionIdDetector;
        return $this;
    }
}
