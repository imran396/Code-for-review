<?php
/**
 * SAM-4974: Move asking bid calculation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AskingBid;

/**
 * Trait NextBidCalculatorCreateTrait
 * @package Sam\Bidding\AskingBid
 */
trait NextBidCalculatorCreateTrait
{
    /**
     * @var NextBidCalculator|null
     */
    protected ?NextBidCalculator $nextBidCalculator = null;

    /**
     * @return NextBidCalculator
     */
    protected function createNextBidCalculator(): NextBidCalculator
    {
        $nextBidCalculator = $this->nextBidCalculator ?: NextBidCalculator::new();
        return $nextBidCalculator;
    }

    /**
     * @param NextBidCalculator $nextBidCalculator
     * @return static
     * @internal
     */
    public function setNextBidCalculator(NextBidCalculator $nextBidCalculator): static
    {
        $this->nextBidCalculator = $nextBidCalculator;
        return $this;
    }
}
