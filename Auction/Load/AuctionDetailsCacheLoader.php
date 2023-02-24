<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug. 31, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Load;

use AuctionDetailsCache;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionDetailsCache\AuctionDetailsCacheReadRepositoryCreateTrait;

/**
 * Contains methods for fetching auction details cached data from DB
 *
 * Class AuctionDetailsCacheLoader
 * @package Sam\Auction\Load
 */
class AuctionDetailsCacheLoader extends CustomizableClass
{
    use AuctionDetailsCacheReadRepositoryCreateTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load entity
     *
     * @param int $auctionId auction.id
     * @param int $key
     * @param bool $isReadOnlyDb
     * @return AuctionDetailsCache|null
     */
    public function load(int $auctionId, int $key, bool $isReadOnlyDb = false): ?AuctionDetailsCache
    {
        $fn = function () use ($auctionId, $key, $isReadOnlyDb) {
            $cacheItem = $this->createAuctionDetailsCacheReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->filterKey($key)
                ->loadEntity();
            return $cacheItem;
        };

        $cacheKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::AUCTION_DETAILS_CACHE_AUCTION_ID_KEY, [$auctionId, $key]);
        $auctionDetailsCache = $this->getEntityMemoryCacheManager()->load($cacheKey, $fn);
        return $auctionDetailsCache;
    }

    /**
     * Load value from auction details cache
     *
     * @param int $auctionId
     * @param int $key
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function loadValue(int $auctionId, int $key, bool $isReadOnlyDb = false): string
    {
        $auctionDetailsCache = $this->load($auctionId, $key, $isReadOnlyDb);
        return $auctionDetailsCache->Value ?? '';
    }
}
