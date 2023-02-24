<?php
/**
 * SAM-3784: Flexible Starting Bid calculation refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\StartingBid;

/**
 * Trait FlexibleStartingBidCalculatorAwareTrait
 * @package Sam\Bidding\StartingBid
 */
trait FlexibleStartingBidCalculatorCreateTrait
{
    /**
     * @var FlexibleStartingBidCalculator|null
     */
    protected ?FlexibleStartingBidCalculator $flexibleStartingBidCalculator = null;

    /**
     * @return FlexibleStartingBidCalculator
     */
    protected function createFlexibleStartingBidCalculator(): FlexibleStartingBidCalculator
    {
        $flexibleStartingBidCalculator = $this->flexibleStartingBidCalculator
            ?: FlexibleStartingBidCalculator::new();
        return $flexibleStartingBidCalculator;
    }

    /**
     * @param FlexibleStartingBidCalculator $flexibleStartingBidCalculator
     * @return static
     * @internal
     */
    public function setFlexibleStartingBidCalculator(FlexibleStartingBidCalculator $flexibleStartingBidCalculator): static
    {
        $this->flexibleStartingBidCalculator = $flexibleStartingBidCalculator;
        return $this;
    }
}
