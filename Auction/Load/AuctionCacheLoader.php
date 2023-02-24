<?php
/**
 * Helping methods for AuctionCache loading
 * We filter by active account and by auction of non-delete statuses.
 * We cache loaded entity in memory.
 * We auto-update db cached data, if we have loaded entity or row with dropped calculated_on timestamp.
 *
 * SAM-4102: AuctionCache Loader class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 13, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Load;

use AuctionCache;
use Sam\Auction\Cache\AuctionDbCacheManagerAwareTrait;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Filter\EntityLoader\AuctionAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCache\AuctionCacheReadRepository;

/**
 * Class AuctionCacheLoader
 * @package Sam\Auction\Load
 */
class AuctionCacheLoader extends EntityLoaderBase
{
    use AuctionAllFilterTrait;
    use AuctionDbCacheManagerAwareTrait;
    use EntityFactoryCreateTrait;
    use EntityMemoryCacheManagerAwareTrait;
    use MemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * Load cache object for auction or create new with actual values.
     *
     * TODO: it creates new record, but doesn't check if auction id exists. It should have loadOrCreate() name then.
     * This fact can incur:
     * a) situation, when we try to load unavailable deleted record, system will create new AuctionCache, and may save in caller. But we shouldn't try to load deleted auctions.
     * b) situation, when we pass incorrect auction id, system will create new AuctionCache, because it doesn't check auction availability and may save in caller. But there shouldn't be such case.
     * Generally, I see, that we call cache loading method with auction id that is taken from Auction object,
     * and we have some checks at controller layer for validity of entities from functional context this controller serves.
     * So that doesn't look to be a problem.
     *
     * @param int|null $auctionId null - absent auction id results with absent null AuctionCache entity
     * @param bool $isReadOnlyDb
     * @return AuctionCache|null
     */
    public function load(?int $auctionId, bool $isReadOnlyDb = false): ?AuctionCache
    {
        if (!$auctionId) {
            return null;
        }

        $fn = function () use ($auctionId, $isReadOnlyDb) {
            $repo = $this->prepareCacheRepository($isReadOnlyDb)
                ->filterAuctionId($auctionId);
            return $this->createIfAbsent($auctionId, $repo->loadEntity());
        };

        $cacheKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::AUCTION_CACHE_AUCTION_ID, $auctionId);
        $auctionCache = $this->getEntityMemoryCacheManager()->load($cacheKey, $fn);
        return $auctionCache;
    }

    /**
     * Load AuctionCache entity or row of fields defined in passed repository.
     * Update AuctionCache data, if CalculatedOn value is dropped or expired
     * @param AuctionCacheReadRepository $repo
     * @param int $auctionId
     * @param int $editorUserId
     * @return array|AuctionCache
     */
    public function loadFreshFromRepo(AuctionCacheReadRepository $repo, int $auctionId, int $editorUserId): array|AuctionCache
    {
        $isRow = count($repo->getSelect()) > 0;
        if ($isRow) {
            $repo->addSelect(['ac.auction_id', 'ac.calculated_on']);
            $result = $repo->loadRow();
            $calculatedOn = $result['calculated_on'] ?? null;
        } else {
            $result = $repo->loadEntity();
            $calculatedOn = $result->CalculatedOn ?? null;
        }

        if (!$calculatedOn) {
            $this->getAuctionDbCacheManager()->refreshByAuctionId($auctionId, $editorUserId);
            $result = $isRow ? $repo->loadRow() : $repo->loadEntity();
        }
        return $result;
    }

    /**
     * @param int $auctionId
     * @param AuctionCache|null $auctionCache
     * @return AuctionCache
     */
    protected function createIfAbsent(int $auctionId, AuctionCache $auctionCache = null): AuctionCache
    {
        if (!$auctionCache) {
            $auctionCache = $this->createEntityFactory()->auctionCache();
            $auctionCache->AuctionId = $auctionId;
            /**
             * Do not save absent entities in loading methods.
             * We incur a side-effect, that may cause un-expected behavior and hidden troubles.
             * Adding such side-effect to query kind of method, we break its conceptual sense to be just an idempotent query method, since we dirty it with command responsibility.
             */
            // if ($this->isAutoSave()) {
            //     $auctionCache->Save($this->isForceInsert(), $this->isForceUpdate());
            // }
        }
        return $auctionCache;
    }
}
