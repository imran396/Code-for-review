<?php
/**
 * SAM-4440: Refactor feed management logic to \Sam\Feed namespace
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/15/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Load;

use Feed;
use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\FeedAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class FeedLoader
 * @package Sam\Feed\Load
 */
class FeedLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use FeedAllFilterTrait;

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
     * @param int|null $feedId null id results to null entity (absent)
     * @param bool $isReadOnlyDb
     * @return Feed|null
     */
    public function load(?int $feedId, bool $isReadOnlyDb = false): ?Feed
    {
        if (!$feedId) {
            return null;
        }

        $fn = function () use ($feedId, $isReadOnlyDb) {
            $feed = $this->prepareRepository($isReadOnlyDb)
                ->filterId($feedId)
                ->loadEntity();
            return $feed;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::FEED_ID, $feedId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $feed = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $feed;
    }

    /**
     * Load Feed object by 'slug'
     * @param string $slug
     * @param int|null $accountId null skips filtering by account
     * @param bool $isReadOnlyDb
     * @return Feed
     */
    public function loadBySlug(string $slug, ?int $accountId = null, bool $isReadOnlyDb = false): ?Feed
    {
        $repo = $this->prepareRepository($isReadOnlyDb)
            ->filterSlug($slug);
        if ($accountId) {
            $repo->filterAccountId($accountId);
        }
        $feed = $repo->loadEntity();
        return $feed;
    }

    /**
     * Return array of feeds included in auction list reports
     *
     * @param int|null $accountId null skips filtering by account
     * @param bool $isReadOnlyDb
     * @return Feed[]
     */
    public function loadFeedsIncludedInAuctionListReports(?int $accountId = null, bool $isReadOnlyDb = false): array
    {
        $feed = $this->prepareRepository($isReadOnlyDb)
            ->filterFeedType(Constants\Feed::TYPE_AUCTIONS)
            ->filterIncludeInReports(true);
        if ($accountId !== null) {
            $feed->filterAccountId($accountId);
        }
        $feed = $feed->loadEntities();
        return $feed;
    }

    /**
     * Return array of feeds included in auction lots reports
     *
     * @param int|null $accountId null skips filtering by account
     * @param bool $isReadOnlyDb
     * @return Feed[]
     */
    public function loadFeedsIncludedInAuctionLotsReports(?int $accountId = null, bool $isReadOnlyDb = false): array
    {
        $feed = $this->prepareRepository($isReadOnlyDb)
            ->filterFeedType(Constants\Feed::TYPE_LOTS)
            ->filterIncludeInReports(true);
        if ($accountId !== null) {
            $feed->filterAccountId($accountId);
        }
        $feed = $feed->loadEntities();
        return $feed;
    }
}
