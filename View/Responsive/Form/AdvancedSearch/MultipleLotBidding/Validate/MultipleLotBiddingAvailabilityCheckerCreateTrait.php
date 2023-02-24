<?php
/**
 * SAM-10327: Remove the "Multiple Lot Bidding" function from mixed accounts scenarios
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\MultipleLotBidding\Validate;

/**
 * Trait MultipleLotBiddingAvailabilityCheckerCreateTrait
 * @package Sam\View\Responsive\Form\AdvancedSearch\MultipleLotBidding\Validate
 */
trait MultipleLotBiddingAvailabilityCheckerCreateTrait
{
    protected ?MultipleLotBiddingAvailabilityChecker $multipleLotBiddingAvailabilityChecker = null;

    /**
     * @return MultipleLotBiddingAvailabilityChecker
     */
    protected function createMultipleLotBiddingAvailabilityChecker(): MultipleLotBiddingAvailabilityChecker
    {
        return $this->multipleLotBiddingAvailabilityChecker ?: MultipleLotBiddingAvailabilityChecker::new();
    }

    /**
     * @param MultipleLotBiddingAvailabilityChecker $multipleLotBiddingAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setMultipleLotBiddingAvailabilityChecker(MultipleLotBiddingAvailabilityChecker $multipleLotBiddingAvailabilityChecker): static
    {
        $this->multipleLotBiddingAvailabilityChecker = $multipleLotBiddingAvailabilityChecker;
        return $this;
    }
}
