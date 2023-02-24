<?php
/**
 * SAM-4682: Auction currency producer and cloner
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/6/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Save;

/**
 * Trait AuctionCurrencyProducerAwareTrait
 * @package Sam\Currency\Save
 */
trait AuctionCurrencyProducerAwareTrait
{
    protected ?AuctionCurrencyProducer $auctionCurrencyProducer = null;

    /**
     * @return AuctionCurrencyProducer
     */
    protected function getAuctionCurrencyProducer(): AuctionCurrencyProducer
    {
        if ($this->auctionCurrencyProducer === null) {
            $this->auctionCurrencyProducer = AuctionCurrencyProducer::new();
        }
        return $this->auctionCurrencyProducer;
    }

    /**
     * @param AuctionCurrencyProducer $auctionCurrencyProducer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionCurrencyProducer(AuctionCurrencyProducer $auctionCurrencyProducer): static
    {
        $this->auctionCurrencyProducer = $auctionCurrencyProducer;
        return $this;
    }
}
