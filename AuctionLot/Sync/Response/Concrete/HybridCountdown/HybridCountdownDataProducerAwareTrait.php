<?php
/**
 * Aware trait for data producer
 *
 * SAM-6388: Active countdown on admin - auction - lots
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown;

/**
 * Trait HybridCountdownDataProducerAwareTrait
 * @package Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown
 */
trait HybridCountdownDataProducerAwareTrait
{
    /**
     * @var HybridCountdownDataProducer|null
     */
    protected ?HybridCountdownDataProducer $hybridCountdownDataProducer = null;

    /**
     * @return HybridCountdownDataProducer
     */
    protected function getHybridCountdownDataProducer(): HybridCountdownDataProducer
    {
        if ($this->hybridCountdownDataProducer === null) {
            $this->hybridCountdownDataProducer = HybridCountdownDataProducer::new();
        }
        return $this->hybridCountdownDataProducer;
    }

    /**
     * @param HybridCountdownDataProducer $hybridCountdownDataProducer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setHybridCountdownDataProducer(HybridCountdownDataProducer $hybridCountdownDataProducer): static
    {
        $this->hybridCountdownDataProducer = $hybridCountdownDataProducer;
        return $this;
    }
}
