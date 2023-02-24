<?php
/**
 * SAM-6434: Refactor auction image logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Image\Save;

/**
 * Trait AuctionImageProducerCreateTrait
 * @package Sam\Auction\Image
 */
trait AuctionImageProducerCreateTrait
{
    protected ?AuctionImageProducer $auctionImageProducer = null;

    /**
     * @return AuctionImageProducer
     */
    protected function createAuctionImageProducer(): AuctionImageProducer
    {
        return $this->auctionImageProducer ?: AuctionImageProducer::new();
    }

    /**
     * @param AuctionImageProducer $auctionImageProducer
     * @return $this
     * @internal
     */
    public function setAuctionImageProducer(AuctionImageProducer $auctionImageProducer): static
    {
        $this->auctionImageProducer = $auctionImageProducer;
        return $this;
    }
}
