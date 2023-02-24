<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/27/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\BidderInterest;

/**
 * Trait BidderInterestManagerAwareTrait
 * @package Sam\Rtb\BidderInterest
 */
trait BidderInterestManagerAwareTrait
{
    /**
     * @var BidderInterestManager|null
     */
    protected ?BidderInterestManager $bidderInterestManager = null;

    /**
     * @return BidderInterestManager
     */
    public function getBidderInterestManager(): BidderInterestManager
    {
        if ($this->bidderInterestManager === null) {
            $this->bidderInterestManager = BidderInterestManager::new();
        }
        return $this->bidderInterestManager;
    }

    /**
     * @param BidderInterestManager $bidderInterestManager
     * @return static
     * @internal
     */
    public function setBidderInterestManager(BidderInterestManager $bidderInterestManager): static
    {
        $this->bidderInterestManager = $bidderInterestManager;
        return $this;
    }
}
