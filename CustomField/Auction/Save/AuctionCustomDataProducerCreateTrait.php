<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/15/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Save;

/**
 * Trait AuctionCustomDataProducerCreateTrait
 * @package
 */
trait AuctionCustomDataProducerCreateTrait
{
    protected ?AuctionCustomDataProducer $auctionCustomDataProducer = null;

    /**
     * @return AuctionCustomDataProducer
     */
    protected function createAuctionCustomDataProducer(): AuctionCustomDataProducer
    {
        return $this->auctionCustomDataProducer ?: AuctionCustomDataProducer::new();
    }

    /**
     * @param AuctionCustomDataProducer $auctionCustomDataBuilder
     * @return static
     * @internal
     */
    public function setAuctionCustomDataProducer(AuctionCustomDataProducer $auctionCustomDataBuilder): static
    {
        $this->auctionCustomDataProducer = $auctionCustomDataBuilder;
        return $this;
    }
}
