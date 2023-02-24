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

use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Cache\AuctionDbCacheManager;
use Sam\Auction\Cache\AuctionDbCacheManagerAwareTrait;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionCache\AuctionCacheWriteRepositoryAwareTrait;

/**
 * Class AuctionCacheLotCountUpdater
 * @package Sam\Auction\Cache
 */
class AuctionCacheLotCountUpdater extends CustomizableClass
{
    use AuctionCacheLoaderAwareTrait;
    use AuctionCacheWriteRepositoryAwareTrait;
    use AuctionDbCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function refresh(int $auctionId, int $editorUserId): void
    {
        $auctionCache = $this->getAuctionCacheLoader()->load($auctionId);
        if (!$auctionCache) {
            log_error('Available AuctionCache record not found for lot count updating' . composeSuffix(['a' => $auctionId]));
            return;
        }

        $fields = [AuctionDbCacheManager::TOTAL_LOTS, AuctionDbCacheManager::TOTAL_ACTIVE_LOTS];
        $values = $this->getAuctionDbCacheManager()->loadAggregatedValues([$auctionId], $fields);
        $auctionCache->TotalLots = $values[$auctionId][AuctionDbCacheManager::TOTAL_LOTS];
        $auctionCache->TotalActiveLots = $values[$auctionId][AuctionDbCacheManager::TOTAL_ACTIVE_LOTS];
        $this->getAuctionCacheWriteRepository()->saveWithModifier($auctionCache, $editorUserId);
    }
}
