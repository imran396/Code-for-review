<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Bidding\BuyNow;

/**
 * Trait BuyNowAvailabilityTimedCheckerAwareTrait
 * @package Sam\Core\Bidding\BuyNow
 */
trait BuyNowAvailabilityTimedCheckerCreateTrait
{
    /**
     * @var BuyNowAvailabilityTimedChecker|null
     */
    protected ?BuyNowAvailabilityTimedChecker $buyNowAvailabilityTimedChecker = null;

    /**
     * @return BuyNowAvailabilityTimedChecker
     */
    protected function createBuyNowAvailabilityTimedChecker(): BuyNowAvailabilityTimedChecker
    {
        return $this->buyNowAvailabilityTimedChecker ?: BuyNowAvailabilityTimedChecker::new();
    }

    /**
     * @param BuyNowAvailabilityTimedChecker $buyNowAvailabilityTimedChecker
     * @return static
     * @internal
     */
    public function setBuyNowAvailabilityTimedChecker(BuyNowAvailabilityTimedChecker $buyNowAvailabilityTimedChecker): static
    {
        $this->buyNowAvailabilityTimedChecker = $buyNowAvailabilityTimedChecker;
        return $this;
    }
}
