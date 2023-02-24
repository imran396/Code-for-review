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
 * Trait AuctionLotDataCollectionProducerCreateTrait
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData
 */
trait AuctionLotDataCollectionProducerCreateTrait
{
    /**
     * @var AuctionLotDataCollectionProducer|null
     */
    protected ?AuctionLotDataCollectionProducer $auctionLotDataCollectionProducer = null;

    /**
     * @return AuctionLotDataCollectionProducer
     */
    protected function createAuctionLotDataCollectionProducer(): AuctionLotDataCollectionProducer
    {
        return $this->auctionLotDataCollectionProducer ?: AuctionLotDataCollectionProducer::new();
    }

    /**
     * @param AuctionLotDataCollectionProducer $auctionLotDataCollectionProducer
     * @return static
     * @internal
     */
    public function setAuctionLotDataCollectionProducer(AuctionLotDataCollectionProducer $auctionLotDataCollectionProducer): static
    {
        $this->auctionLotDataCollectionProducer = $auctionLotDataCollectionProducer;
        return $this;
    }
}
