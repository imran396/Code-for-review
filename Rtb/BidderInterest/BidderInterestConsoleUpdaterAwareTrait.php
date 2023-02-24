<?php
/**
 * SAM-1023: Live Clerking Improvements & Bidder Interest
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/6/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\BidderInterest;

/**
 * Trait BidderInterestTimeTrackerAwareTrait
 * @package
 */
trait BidderInterestConsoleUpdaterAwareTrait
{
    /**
     * @var BidderInterestConsoleUpdater|null
     */
    protected ?BidderInterestConsoleUpdater $bidderInterestConsoleUpdater = null;

    /**
     * @return BidderInterestConsoleUpdater
     */
    protected function getBidderInterestConsoleUpdater(): BidderInterestConsoleUpdater
    {
        if ($this->bidderInterestConsoleUpdater === null) {
            $this->bidderInterestConsoleUpdater = BidderInterestConsoleUpdater::new();
        }
        return $this->bidderInterestConsoleUpdater;
    }

    /**
     * @param BidderInterestConsoleUpdater $bidderInterestConsoleUpdater
     * @return static
     * @internal
     */
    public function setBidderInterestConsoleUpdater(BidderInterestConsoleUpdater $bidderInterestConsoleUpdater): static
    {
        $this->bidderInterestConsoleUpdater = $bidderInterestConsoleUpdater;
        return $this;
    }
}
