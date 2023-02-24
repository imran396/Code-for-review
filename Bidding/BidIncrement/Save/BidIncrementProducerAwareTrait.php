<?php
/**
 * Trait for BidIncrementProducer
 *
 * SAM-4474: Modules for Bid Increments
 *
 * @author        Victor Pautoff
 * @since         Oct 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\BidIncrement\Save;

/**
 * Trait BidIncrementProducerAwareTrait
 * @package Sam\Bidding\BidIncrement\Save
 */
trait BidIncrementProducerAwareTrait
{
    /**
     * @var BidIncrementProducer|null
     */
    protected ?BidIncrementProducer $bidIncrementProducer = null;

    /**
     * @return BidIncrementProducer
     */
    protected function getBidIncrementProducer(): BidIncrementProducer
    {
        if ($this->bidIncrementProducer === null) {
            $this->bidIncrementProducer = BidIncrementProducer::new();
        }
        return $this->bidIncrementProducer;
    }

    /**
     * @param BidIncrementProducer $bidIncrementProducer
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBidIncrementProducer(BidIncrementProducer $bidIncrementProducer): static
    {
        $this->bidIncrementProducer = $bidIncrementProducer;
        return $this;
    }
}
