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

namespace Sam\Bidding\BuyNow\QuantityLotCloner\Internal;

/**
 * Trait BuyNowItemNumCloneStrategyCreateTrait
 * @package Sam\Bidding\BuyNow\QuantityLotCloner
 * @internal
 */
trait BuyNowItemNumCloneStrategyCreateTrait
{
    protected ?BuyNowItemNumCloneStrategy $buyNowItemNumCloneStrategy = null;

    /**
     * @return BuyNowItemNumCloneStrategy
     */
    protected function createBuyNowItemNumCloneStrategy(): BuyNowItemNumCloneStrategy
    {
        return $this->buyNowItemNumCloneStrategy ?: BuyNowItemNumCloneStrategy::new();
    }

    /**
     * @param BuyNowItemNumCloneStrategy $buyNowItemNumCloneStrategy
     * @return static
     * @internal
     */
    public function setBuyNowItemNumCloneStrategy(BuyNowItemNumCloneStrategy $buyNowItemNumCloneStrategy): static
    {
        $this->buyNowItemNumCloneStrategy = $buyNowItemNumCloneStrategy;
        return $this;
    }
}
