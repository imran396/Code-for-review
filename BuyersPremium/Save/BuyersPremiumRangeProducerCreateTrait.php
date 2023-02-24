<?php
/**
 * SAM-10464: Separate BP manager to services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Save;

/**
 * Trait BuyersPremiumProducerCreateTrait
 * @package Sam\BuyersPreimum\Save
 */
trait BuyersPremiumRangeProducerCreateTrait
{
    protected ?BuyersPremiumRangeProducer $buyersPremiumRangeProducer = null;

    /**
     * @return BuyersPremiumRangeProducer
     */
    protected function createBuyersPremiumRangeProducer(): BuyersPremiumRangeProducer
    {
        return $this->buyersPremiumRangeProducer ?: BuyersPremiumRangeProducer::new();
    }

    /**
     * @param BuyersPremiumRangeProducer $buyersPremiumRangeProducer
     * @return $this
     * @internal
     */
    public function setBuyersPremiumRangeProducer(BuyersPremiumRangeProducer $buyersPremiumRangeProducer): static
    {
        $this->buyersPremiumRangeProducer = $buyersPremiumRangeProducer;
        return $this;
    }
}
