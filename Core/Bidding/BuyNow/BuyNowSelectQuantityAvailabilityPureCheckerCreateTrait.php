<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Bidding\BuyNow;

/**
 * Trait BuyNowSelectQuantityAvailabilityPureCheckerCreateTrait
 * @package Sam\Core\Bidding\BuyNow
 */
trait BuyNowSelectQuantityAvailabilityPureCheckerCreateTrait
{
    /**
     * @var BuyNowSelectQuantityAvailabilityPureChecker|null
     */
    protected ?BuyNowSelectQuantityAvailabilityPureChecker $buyNowSelectQuantityAvailabilityPureChecker = null;

    /**
     * @return BuyNowSelectQuantityAvailabilityPureChecker
     */
    protected function createBuyNowSelectQuantityAvailabilityPureChecker(): BuyNowSelectQuantityAvailabilityPureChecker
    {
        return $this->buyNowSelectQuantityAvailabilityPureChecker ?: BuyNowSelectQuantityAvailabilityPureChecker::new();
    }

    /**
     * @param BuyNowSelectQuantityAvailabilityPureChecker $buyNowSelectQuantityAvailabilityPureChecker
     * @return static
     * @internal
     */
    public function setBuyNowSelectQuantityAvailabilityPureChecker(BuyNowSelectQuantityAvailabilityPureChecker $buyNowSelectQuantityAvailabilityPureChecker): static
    {
        $this->buyNowSelectQuantityAvailabilityPureChecker = $buyNowSelectQuantityAvailabilityPureChecker;
        return $this;
    }
}
