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
 * Trait BuyNowAvailabilityLiveCheckerAwareTrait
 * @package Sam\Core\Bidding\BuyNow
 */
trait BuyNowAvailabilityLiveCheckerCreateTrait
{
    /**
     * @var BuyNowAvailabilityLiveChecker|null
     */
    protected ?BuyNowAvailabilityLiveChecker $buyNowAvailabilityLiveChecker = null;

    /**
     * @return BuyNowAvailabilityLiveChecker
     */
    protected function createBuyNowAvailabilityLiveChecker(): BuyNowAvailabilityLiveChecker
    {
        return $this->buyNowAvailabilityLiveChecker ?: BuyNowAvailabilityLiveChecker::new();
    }

    /**
     * @param BuyNowAvailabilityLiveChecker $buyNowAvailabilityLiveChecker
     * @return static
     * @internal
     */
    public function setBuyNowAvailabilityLiveChecker(BuyNowAvailabilityLiveChecker $buyNowAvailabilityLiveChecker): static
    {
        $this->buyNowAvailabilityLiveChecker = $buyNowAvailabilityLiveChecker;
        return $this;
    }
}
