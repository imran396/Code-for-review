<?php
/**
 * SAM-6042: Extract lot count updating logic for auction cache to separate class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           4/30/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\LotCount;

/**
 * Trait AuctionCacheLotCountUpdaterAwareTrait
 * @package Sam\Auction\Cache
 */
trait AuctionCacheLotCountUpdaterAwareTrait
{
    protected ?AuctionCacheLotCountUpdater $auctionCacheLotCountUpdater = null;

    /**
     * @return AuctionCacheLotCountUpdater
     */
    protected function getAuctionCacheLotCountUpdater(): AuctionCacheLotCountUpdater
    {
        if ($this->auctionCacheLotCountUpdater === null) {
            $this->auctionCacheLotCountUpdater = AuctionCacheLotCountUpdater::new();
        }
        return $this->auctionCacheLotCountUpdater;
    }

    /**
     * @param AuctionCacheLotCountUpdater $auctionCacheLotCountUpdater
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionCacheLotCountUpdater(AuctionCacheLotCountUpdater $auctionCacheLotCountUpdater): static
    {
        $this->auctionCacheLotCountUpdater = $auctionCacheLotCountUpdater;
        return $this;
    }
}
