<?php
/**
 * SAM-6019: Auction end date overhaul
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\AuctionDynamic;

/**
 * Trait AuctionDynamicProducerCreateTrait
 * @package Sam\Auction\AuctionDynamic
 */
trait AuctionDynamicProducerCreateTrait
{
    protected ?AuctionDynamicProducer $auctionDynamicProducer = null;

    /**
     * @return AuctionDynamicProducer
     */
    protected function createAuctionDynamicProducer(): AuctionDynamicProducer
    {
        return $this->auctionDynamicProducer ?: AuctionDynamicProducer::new();
    }

    /**
     * @param AuctionDynamicProducer $auctionDynamicProducer
     * @return static
     * @internal
     */
    public function setAuctionDynamicProducer(AuctionDynamicProducer $auctionDynamicProducer): static
    {
        $this->auctionDynamicProducer = $auctionDynamicProducer;
        return $this;
    }
}
