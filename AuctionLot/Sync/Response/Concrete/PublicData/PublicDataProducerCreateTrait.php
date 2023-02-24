<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 26, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData;

/**
 * Trait PublicDataProducerCreateTrait
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData
 */
trait PublicDataProducerCreateTrait
{
    protected ?PublicDataProducer $publicDataProducer = null;

    /**
     * @return PublicDataProducer
     */
    protected function createPublicDataProducer(): PublicDataProducer
    {
        return $this->publicDataProducer ?: PublicDataProducer::new();
    }

    /**
     * @param PublicDataProducer $publicDataProducer
     * @return static
     * @internal
     */
    public function setPublicDataProducer(PublicDataProducer $publicDataProducer): static
    {
        $this->publicDataProducer = $publicDataProducer;
        return $this;
    }
}
