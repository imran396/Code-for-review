<?php
/**
 * SAM-6431: Refactor Auction_Lots_DbCacheManager for 2020 year version
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Cache\SeoUrl\Save;

/**
 * Trait AuctionLotCacheSeoUrlUpdaterCreateTrait
 * @package
 */
trait AuctionLotCacheSeoUrlUpdaterCreateTrait
{
    /**
     * @var AuctionLotCacheSeoUrlUpdater|null
     */
    protected ?AuctionLotCacheSeoUrlUpdater $auctionLotCacheSeoUrlUpdater = null;

    /**
     * @return AuctionLotCacheSeoUrlUpdater
     */
    protected function createAuctionLotCacheSeoUrlUpdater(): AuctionLotCacheSeoUrlUpdater
    {
        return $this->auctionLotCacheSeoUrlUpdater ?: AuctionLotCacheSeoUrlUpdater::new();
    }

    /**
     * @param AuctionLotCacheSeoUrlUpdater $auctionLotCacheSeoUrlUpdater
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionLotCacheSeoUrlUpdater(AuctionLotCacheSeoUrlUpdater $auctionLotCacheSeoUrlUpdater): static
    {
        $this->auctionLotCacheSeoUrlUpdater = $auctionLotCacheSeoUrlUpdater;
        return $this;
    }
}
