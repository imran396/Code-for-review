<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData;

/**
 * Trait AuctionTotalDataProducerCreateTrait
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData
 */
trait AuctionTotalDataProducerCreateTrait
{
    /**
     * @var AuctionTotalDataProducer|null
     */
    protected ?AuctionTotalDataProducer $auctionTotalDataProducer = null;

    /**
     * @return AuctionTotalDataProducer
     */
    protected function createAuctionTotalDataProducer(): AuctionTotalDataProducer
    {
        return $this->auctionTotalDataProducer ?: AuctionTotalDataProducer::new();
    }

    /**
     * @param AuctionTotalDataProducer $auctionTotalDataProducer
     * @return static
     * @internal
     */
    public function setAuctionTotalDataProducer(AuctionTotalDataProducer $auctionTotalDataProducer): static
    {
        $this->auctionTotalDataProducer = $auctionTotalDataProducer;
        return $this;
    }
}
