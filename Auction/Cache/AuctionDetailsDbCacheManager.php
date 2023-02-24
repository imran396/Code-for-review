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

namespace Sam\Auction\Cache;

use AuctionDetailsCache;
use Generator;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Details\Auction\SeoUrl\Builder as SeoUrlBuilder;
use Sam\Details\Auction\Web\Caption\CachedTemplatePreBuilder;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionDetailsCache\AuctionDetailsCacheReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionDetailsCache\AuctionDetailsCacheReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionDetailsCache\AuctionDetailsCacheWriteRepositoryAwareTrait;

/**
 * Responsible for caching auction seo url and caption
 *
 * Class AuctionDetailsDbCacheManager
 * @package Sam\Auction\Cache
 */
class AuctionDetailsDbCacheManager extends CustomizableClass
{
    use AuctionDetailsCacheReadRepositoryCreateTrait;
    use AuctionDetailsCacheWriteRepositoryAwareTrait;
    use AuctionReadRepositoryCreateTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;

    private const CHUNK_SIZE = 200;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create initial auction details cache records
     *
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function createInitialRecords(int $auctionId, int $editorUserId): void
    {
        foreach (Constants\AuctionDetailsCache::ALL_KEYS as $key) {
            $cacheItem = $this->createEntityFactory()->auctionDetailsCache();
            $cacheItem->AuctionId = $auctionId;
            $cacheItem->Key = $key;
            $this->getAuctionDetailsCacheWriteRepository()->saveWithModifier($cacheItem, $editorUserId);
        }
    }

    /**
     * Generate and update auction seo url and caption cache
     *
     * @param array $auctionIds
     * @param int $editorUserId
     * @param bool $force If true refresh even if cache is not expired
     */
    public function refresh(array $auctionIds, int $editorUserId, bool $force = false): void
    {
        $resultGenerator = $this->yieldUpdating($auctionIds, $editorUserId, $force);

        while ($resultGenerator->valid()) {
            $resultGenerator->next();
        }
    }

    /**
     * Generate and update auction seo url and caption cache using the generator
     *
     * @param array $auctionIds
     * @param int $editorUserId
     * @param bool $force
     * @return Generator Processed Auction id
     */
    public function yieldUpdating(array $auctionIds, int $editorUserId, bool $force = false): Generator
    {
        if (!$auctionIds) {
            return;
        }
        $auctionIdListGroupedByAccountId = $this->buildAuctionIdListGroupedByAccountId($auctionIds);

        foreach ($auctionIdListGroupedByAccountId as $accountId => $auctionIdList) {
            $cacheRepository = $this->prepareAuctionDetailsCacheRepository($auctionIdList, $force, self::CHUNK_SIZE);
            do {
                $cacheChunk = $cacheRepository->loadEntities();
                if (!$cacheChunk) {
                    break;
                }

                $seoUrlValues = $this->buildSeoUrlValues($accountId, $cacheChunk);

                foreach ($cacheChunk as $cacheItem) {
                    if ($cacheItem->Key === Constants\AuctionDetailsCache::SEO_URL) {
                        $cacheItem->Value = $seoUrlValues[$cacheItem->AuctionId] ?? '';
                        log_debug('Auction SEO_URL cache was updated' . composeSuffix(['a' => $cacheItem->AuctionId, 'v' => $cacheItem->Value]));
                    } elseif ($cacheItem->Key === Constants\AuctionDetailsCache::CAPTION) {
                        $cacheItem->Value = CachedTemplatePreBuilder::new()->render($cacheItem->AuctionId, $accountId);
                        log_debug('Auction CAPTION cache was updated' . composeSuffix(['a' => $cacheItem->AuctionId, 'v' => $cacheItem->Value]));
                    }
                    $cacheItem->CalculatedOn = $this->getCurrentDateUtc();
                    $this->getAuctionDetailsCacheWriteRepository()->saveWithModifier($cacheItem, $editorUserId);
                    yield $cacheItem->AuctionId => $cacheItem->Key;
                }
            } while (true);
        }
    }

    /**
     * Return auction ids awaiting cache update
     *
     * @param bool $force
     * @param array $accountIds
     * @return array
     */
    public function detectExpectedRefreshCacheAuctionIds(bool $force = false, array $accountIds = []): array
    {
        $repository = $this->createAuctionDetailsCacheReadRepository();
        if (!$force) {
            $repository->filterCalculatedOn(null);
        }

        if ($accountIds) {
            $repository
                ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
                ->joinAuctionFilterAccountId($accountIds);
        }

        $rows = $repository
            ->select(['auction_id'])
            ->loadRows();

        $auctionIds = ArrayHelper::flattenArray($rows);
        return $auctionIds;
    }

    /**
     * @param int[] $auctionIds
     * @param bool $force
     * @param int|null $chunkSize
     * @return AuctionDetailsCacheReadRepository
     */
    private function prepareAuctionDetailsCacheRepository(array $auctionIds, bool $force, ?int $chunkSize): AuctionDetailsCacheReadRepository
    {
        $repository = $this->createAuctionDetailsCacheReadRepository()
            ->filterAuctionId($auctionIds)
            ->setChunkSize($chunkSize);
        if (!$force) {
            $repository->filterCalculatedOn(null);
        }
        return $repository;
    }

    /**
     * @param array $auctionIds
     * @return array
     */
    private function buildAuctionIdListGroupedByAccountId(array $auctionIds): array
    {
        $list = [];
        $rows = $this->loadAuctionAccounts($auctionIds);
        foreach ($rows as $row) {
            $list[$row['account_id']][] = (int)$row['id'];
        }
        return $list;
    }

    /**
     * @param array $auctionIds
     * @return array
     */
    private function loadAuctionAccounts(array $auctionIds): array
    {
        $auctionsAccounts = $this->createAuctionReadRepository()
            ->select(['id', 'account_id'])
            ->filterId($auctionIds)
            ->loadRows();

        return $auctionsAccounts;
    }

    /**
     * @param int $accountId
     * @param AuctionDetailsCache[] $cacheItems
     * @return string[]
     */
    protected function buildSeoUrlValues(int $accountId, array $cacheItems): array
    {
        $expectedRefreshSeoUrlAuctionIds = $this->detectExpectedRefreshSeoUrlAuctionIds($cacheItems);
        if (!$expectedRefreshSeoUrlAuctionIds) {
            return [];
        }
        $seoUrls = SeoUrlBuilder::new()
            ->setSystemAccountId($accountId)
            ->setAuctionIds($expectedRefreshSeoUrlAuctionIds)
            ->render();
        return $seoUrls;
    }

    /**
     * @param AuctionDetailsCache[] $detailsCacheList
     * @return array
     */
    private function detectExpectedRefreshSeoUrlAuctionIds(array $detailsCacheList): array
    {
        $ids = [];
        foreach ($detailsCacheList as $cacheItem) {
            if ($cacheItem->Key === Constants\AuctionDetailsCache::SEO_URL) {
                $ids[] = $cacheItem->AuctionId;
            }
        }
        return $ids;
    }
}
