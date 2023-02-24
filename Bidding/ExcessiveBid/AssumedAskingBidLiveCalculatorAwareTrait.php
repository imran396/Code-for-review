<?php
/**
 * SAM-5229: Outrageous bid alert reveals hidden high absentee bid
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/20/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\ExcessiveBid;

/**
 * Trait AssumedAskingBidLiveCalculatorAwareTrait
 * @package Sam\Bidding\ExcessiveBid
 */
trait AssumedAskingBidLiveCalculatorAwareTrait
{
    protected ?AssumedAskingBidLiveCalculator $assumedAskingBidLiveCalculator = null;

    /**
     * @return AssumedAskingBidLiveCalculator
     */
    protected function getAssumedAskingBidLiveCalculator(): AssumedAskingBidLiveCalculator
    {
        if ($this->assumedAskingBidLiveCalculator === null) {
            $this->assumedAskingBidLiveCalculator = AssumedAskingBidLiveCalculator::new();
        }
        return $this->assumedAskingBidLiveCalculator;
    }

    /**
     * @param AssumedAskingBidLiveCalculator $assumedAskingBidLiveCalculator
     * @return static
     * @internal
     */
    public function setAssumedAskingBidLiveCalculator(AssumedAskingBidLiveCalculator $assumedAskingBidLiveCalculator): static
    {
        $this->assumedAskingBidLiveCalculator = $assumedAskingBidLiveCalculator;
        return $this;
    }
}
