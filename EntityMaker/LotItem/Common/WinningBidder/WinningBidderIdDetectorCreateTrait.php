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

namespace Sam\EntityMaker\LotItem\Common\WinningBidder;

/**
 * Trait WinningBidderIdDetectorCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait WinningBidderIdDetectorCreateTrait
{
    /**
     * @var WinningBidderIdDetector|null
     */
    protected ?WinningBidderIdDetector $winningBidderIdDetector = null;

    /**
     * @return WinningBidderIdDetector
     */
    protected function createWinningBidderIdDetector(): WinningBidderIdDetector
    {
        return $this->winningBidderIdDetector ?: WinningBidderIdDetector::new();
    }

    /**
     * @param WinningBidderIdDetector $winningBidderIdDetector
     * @return static
     * @internal
     */
    public function setWinningBidderIdDetector(WinningBidderIdDetector $winningBidderIdDetector): static
    {
        $this->winningBidderIdDetector = $winningBidderIdDetector;
        return $this;
    }
}
