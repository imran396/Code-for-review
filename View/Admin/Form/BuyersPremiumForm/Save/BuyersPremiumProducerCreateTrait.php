<?php
/**
 * SAM-5950: Refactor buyers premium page at admin side
 * SAM-5454: Extract data loading from form classes
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Save;

/**
 * Trait BuyersPremiumProducerCreateTrait
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Save
 */
trait BuyersPremiumProducerCreateTrait
{
    protected ?BuyersPremiumProducer $buyersPremiumProducer = null;

    /**
     * @return BuyersPremiumProducer
     */
    protected function createBuyersPremiumProducer(): BuyersPremiumProducer
    {
        return $this->buyersPremiumProducer ?: BuyersPremiumProducer::new();
    }

    /**
     * @param BuyersPremiumProducer $buyersPremiumProducer
     * @return static
     * @internal
     */
    public function setBuyersPremiumProducer(BuyersPremiumProducer $buyersPremiumProducer): static
    {
        $this->buyersPremiumProducer = $buyersPremiumProducer;
        return $this;
    }
}
