<?php
/**
 * Load Auction Auctioneers
 *
 * SAM-4453 : Apply Auction Auctioneer loader
 * https://bidpath.atlassian.net/browse/SAM-4453
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff,Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 16, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Auctioneer\Load;

use AuctionAuctioneer;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\AuctionAuctioneer\AuctionAuctioneerReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionAuctioneer\AuctionAuctioneerReadRepositoryCreateTrait;

/**
 * Class AuctionAuctioneerLoader
 * @package Sam\Auction\Auctioneer\Load
 */
class AuctionAuctioneerLoader extends EntityLoaderBase
{
    use AuctionAuctioneerReadRepositoryCreateTrait;
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
     * @param int|null $auctionAuctioneerId
     * @param bool $isReadOnlyDb
     * @return AuctionAuctioneer|null
     */
    public function load(?int $auctionAuctioneerId, bool $isReadOnlyDb = false): ?AuctionAuctioneer
    {
        if (!$auctionAuctioneerId) {
            return null;
        }

        $fn = function () use ($auctionAuctioneerId, $isReadOnlyDb) {
            $auctionAuctioneer = $this->prepareRepository($isReadOnlyDb)
                ->filterId($auctionAuctioneerId)
                ->loadEntity();
            return $auctionAuctioneer;
        };

        $cacheKey = sprintf(Constants\MemoryCache::AUCTION_AUCTIONEER_ID, $auctionAuctioneerId);
        $auctionAuctioneer = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $auctionAuctioneer;
    }

    /**
     * Load AuctionAuctioneer by Name
     *
     * @param int $accountId auction_auctioneer.account_id
     * @param string $name
     * @param bool $isReadOnlyDb
     * @return AuctionAuctioneer|null
     */
    public function loadByName(int $accountId, string $name, bool $isReadOnlyDb = false): ?AuctionAuctioneer
    {
        $auctionAuctioneer = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterName($name)
            ->loadEntity();
        return $auctionAuctioneer;
    }

    /**
     * Load all active AuctionAuctioneer records
     *
     * @param int $accountId auction_auctioneer.account_id
     * @param bool $isReadOnlyDb
     * @return AuctionAuctioneer[]
     */
    public function loadAll(int $accountId, bool $isReadOnlyDb = false): array
    {
        $auctionAuctioneers = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->orderByName()
            ->loadEntities();
        return $auctionAuctioneers;
    }

    /**
     * @param string[] $select - define fetched columns
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAllSelected(array $select, int $accountId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->select($select)
            ->orderByName()
            ->loadRows();
        return $rows;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionAuctioneerReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): AuctionAuctioneerReadRepository
    {
        $repo = $this->createAuctionAuctioneerReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $repo;
    }
}
