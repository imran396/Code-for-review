<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
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

namespace Sam\AuctionLot\LotNo\Fill\Save;

/**
 * Trait LotNoByAutoIncrementProducerCreateTrait
 * @package Sam\AuctionLot\LotNo\Fill\Save
 */
trait LotNoByAutoIncrementProducerCreateTrait
{
    protected ?LotNoByAutoIncrementProducer $lotNoByAutoIncrementProducer = null;

    /**
     * @return LotNoByAutoIncrementProducer
     */
    protected function createLotNoByAutoIncrementProducer(): LotNoByAutoIncrementProducer
    {
        return $this->lotNoByAutoIncrementProducer ?: LotNoByAutoIncrementProducer::new();
    }

    /**
     * @param LotNoByAutoIncrementProducer $lotNoByAutoIncrementProducer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotNoByAutoIncrementProducer(LotNoByAutoIncrementProducer $lotNoByAutoIncrementProducer): static
    {
        $this->lotNoByAutoIncrementProducer = $lotNoByAutoIncrementProducer;
        return $this;
    }
}
