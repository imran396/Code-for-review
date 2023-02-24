<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow;

/**
 * Trait BuyNowSelectQuantityAvailabilityCheckerCreateTrait
 * @package Sam\Bidding\BuyNow
 */
trait BuyNowSelectQuantityAvailabilityCheckerCreateTrait
{
    /**
     * @var BuyNowSelectQuantityAvailabilityChecker|null
     */
    protected ?BuyNowSelectQuantityAvailabilityChecker $buyNowSelectQuantityAvailabilityChecker = null;

    /**
     * @return BuyNowSelectQuantityAvailabilityChecker
     */
    protected function createBuyNowSelectQuantityAvailabilityChecker(): BuyNowSelectQuantityAvailabilityChecker
    {
        return $this->buyNowSelectQuantityAvailabilityChecker ?: BuyNowSelectQuantityAvailabilityChecker::new();
    }

    /**
     * @param BuyNowSelectQuantityAvailabilityChecker $buyNowSelectQuantityAvailabilityChecker
     * @return static
     * @internal
     */
    public function setBuyNowSelectQuantityAvailabilityChecker(BuyNowSelectQuantityAvailabilityChecker $buyNowSelectQuantityAvailabilityChecker): static
    {
        $this->buyNowSelectQuantityAvailabilityChecker = $buyNowSelectQuantityAvailabilityChecker;
        return $this;
    }
}
