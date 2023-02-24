<?php
/**
 * SAM-5637: Extract logic from Lot_Factory
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Save;

/**
 * Trait TimedItemProducerCreateTrait
 * @package Sam\AuctionLot\Save
 */
trait TimedItemProducerCreateTrait
{
    /**
     * @var TimedItemProducer|null
     */
    protected ?TimedItemProducer $timedItemProducer = null;

    /**
     * @return TimedItemProducer
     */
    protected function createTimedItemProducer(): TimedItemProducer
    {
        return $this->timedItemProducer ?: TimedItemProducer::new();
    }

    /**
     * @param TimedItemProducer $timedItemProducer
     * @return static
     * @internal
     */
    public function setTimedItemProducer(TimedItemProducer $timedItemProducer): static
    {
        $this->timedItemProducer = $timedItemProducer;
        return $this;
    }
}
