<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow\QuantityLotCloner\Internal;

/**
 * Trait BuyNowLotNumCloneStrategyCreateTrait
 * @package Sam\Bidding\BuyNow\QuantityLotCloner
 * @internal
 */
trait BuyNowLotNumCloneStrategyCreateTrait
{
    protected ?BuyNowLotNumCloneStrategy $buyNowLotNumCloneStrategy = null;

    /**
     * @return BuyNowLotNumCloneStrategy
     */
    protected function createBuyNowLotNumCloneStrategy(): BuyNowLotNumCloneStrategy
    {
        return $this->buyNowLotNumCloneStrategy ?: BuyNowLotNumCloneStrategy::new();
    }

    /**
     * @param BuyNowLotNumCloneStrategy $buyNowLotNumCloneStrategy
     * @return static
     * @internal
     */
    public function setBuyNowLotNumCloneStrategy(BuyNowLotNumCloneStrategy $buyNowLotNumCloneStrategy): static
    {
        $this->buyNowLotNumCloneStrategy = $buyNowLotNumCloneStrategy;
        return $this;
    }
}
