<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Produce\Internal\ShippingCharge;

/**
 * Trait ShippingChargeProducerCreateTrait
 * @package Sam\Invoice\Legacy\Generate\Produce\Internal\ShippingCharge
 */
trait ShippingChargeProducerCreateTrait
{
    protected ?ShippingChargeProducer $shippingChargeProducer = null;

    /**
     * @return ShippingChargeProducer
     */
    protected function createShippingChargeProducer(): ShippingChargeProducer
    {
        return $this->shippingChargeProducer ?: ShippingChargeProducer::new();
    }

    /**
     * @param ShippingChargeProducer $shippingChargeProducer
     * @return $this
     * @internal
     */
    public function setShippingChargeProducer(ShippingChargeProducer $shippingChargeProducer): static
    {
        $this->shippingChargeProducer = $shippingChargeProducer;
        return $this;
    }
}
