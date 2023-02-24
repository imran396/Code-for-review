<?php
/**
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/13/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save;

/**
 * Trait SettlementProducerAwareTrait
 * @package Sam\Settlement\Save
 */
trait SettlementProducerAwareTrait
{
    protected ?SettlementProducer $settlementProducer = null;

    /**
     * @return SettlementProducer
     */
    public function getSettlementProducer(): SettlementProducer
    {
        if ($this->settlementProducer === null) {
            $this->settlementProducer = SettlementProducer::new();
        }
        return $this->settlementProducer;
    }

    /**
     * @param SettlementProducer $settlementProducer
     * @return static
     * @internal
     */
    public function setSettlementProducer(SettlementProducer $settlementProducer): static
    {
        $this->settlementProducer = $settlementProducer;
        return $this;
    }
}
