<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/31/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Auction\Save;

/**
 * Trait AuctionRtbdProducerAwareTrait
 * @package
 */
trait AuctionRtbdProducerAwareTrait
{
    /**
     * @var AuctionRtbdProducer|null
     */
    protected ?AuctionRtbdProducer $auctionRtbdProducer = null;

    /**
     * @return AuctionRtbdProducer
     */
    protected function getAuctionRtbdProducer(): AuctionRtbdProducer
    {
        if ($this->auctionRtbdProducer === null) {
            $this->auctionRtbdProducer = AuctionRtbdProducer::new();
        }
        return $this->auctionRtbdProducer;
    }

    /**
     * @param AuctionRtbdProducer $auctionRtbdProducer
     * @return static
     * @internal
     */
    public function setAuctionRtbdProducer(AuctionRtbdProducer $auctionRtbdProducer): static
    {
        $this->auctionRtbdProducer = $auctionRtbdProducer;
        return $this;
    }
}
