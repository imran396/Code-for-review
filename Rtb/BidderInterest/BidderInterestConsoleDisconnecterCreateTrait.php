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
 * Trait BidderInterestConsoleDisconnecterCreateTrait
 * @package
 */
trait BidderInterestConsoleDisconnecterCreateTrait
{
    /**
     * @var BidderInterestConsoleDisconnecter|null
     */
    protected ?BidderInterestConsoleDisconnecter $bidderInterestConsoleDisconnecter = null;

    /**
     * @return BidderInterestConsoleDisconnecter
     */
    protected function createBidderInterestDisconnecter(): BidderInterestConsoleDisconnecter
    {
        return $this->bidderInterestConsoleDisconnecter ?: BidderInterestConsoleDisconnecter::new();
    }

    /**
     * @param BidderInterestConsoleDisconnecter $bidderInterestConsoleDisconnecter
     * @return static
     * @internal
     */
    public function setBidderInterestDisconnecter(BidderInterestConsoleDisconnecter $bidderInterestConsoleDisconnecter): static
    {
        $this->bidderInterestConsoleDisconnecter = $bidderInterestConsoleDisconnecter;
        return $this;
    }
}
