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

namespace Sam\AuctionLot\Cache\ViewCount\Save;

/**
 * Trait AuctionLotCacheViewCountUpdaterCreateTrait
 * @package
 */
trait AuctionLotCacheViewCountUpdaterCreateTrait
{
    /**
     * @var AuctionLotCacheViewCountUpdater|null
     */
    protected ?AuctionLotCacheViewCountUpdater $auctionLotCacheViewCountUpdater = null;

    /**
     * @return AuctionLotCacheViewCountUpdater
     */
    protected function createAuctionLotCacheViewCountUpdater(): AuctionLotCacheViewCountUpdater
    {
        return $this->auctionLotCacheViewCountUpdater ?: AuctionLotCacheViewCountUpdater::new();
    }

    /**
     * @param AuctionLotCacheViewCountUpdater $auctionLotCacheViewCountUpdater
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionLotCacheViewCountUpdater(AuctionLotCacheViewCountUpdater $auctionLotCacheViewCountUpdater): static
    {
        $this->auctionLotCacheViewCountUpdater = $auctionLotCacheViewCountUpdater;
        return $this;
    }
}
