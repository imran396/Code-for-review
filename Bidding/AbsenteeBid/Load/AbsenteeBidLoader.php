<?php
/**
 * Help methods for AbsenteeBid loading
 *
 * SAM-4153: Absentee bid loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 20, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AbsenteeBid\Load;

use AbsenteeBid;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepository;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;

/**
 * Class AbsenteeBidLoader
 * @package Sam\Bidding\AbsenteeBid\Load
 */
class AbsenteeBidLoader extends EntityLoaderBase
{
    use AbsenteeBidReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;

    /**
     * Filter results by these statuses
     * @var int[]|null
     */
    protected ?array $lotStatusIds;

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
        $this->lotStatusIds = Constants\Lot::$availableLotStatuses;
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->lotStatusIds = null;
        return $this;
    }

    /**
     * Load AbsenteeBid by user.id, auction.id and lot_item.id,
     * its auction lot should have available status, account and lot item should be active.
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return AbsenteeBid|null
     */
    public function load(?int $lotItemId, ?int $auctionId, ?int $userId, bool $isReadOnlyDb = false): ?AbsenteeBid
    {
        $lotItemId = Cast::toInt($lotItemId, Constants\Type::F_INT_POSITIVE);
        $auctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        $userId = Cast::toInt($userId, Constants\Type::F_INT_POSITIVE);
        if (
            !$lotItemId
            || !$auctionId
            || !$userId
        ) {
            return null;
        }

        $fn = function () use ($userId, $lotItemId, $auctionId, $isReadOnlyDb) {
            $repo = $this->prepareRepository($isReadOnlyDb);
            $absenteeBid = $repo
                ->filterAuctionId($auctionId)
                ->filterLotItemId($lotItemId)
                ->filterUserId($userId)
                ->loadEntity();
            return $absenteeBid;
        };

        $cacheKey = sprintf(Constants\MemoryCache::ABSENTEE_BID_USER_ID_LOT_ITEM_ID_AUCTION_ID, $userId, $lotItemId, $auctionId);
        $absenteeBid = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $absenteeBid;
    }

    /**
     * Load AbsenteeBid by id
     * @param int|null $absenteeBidId absentee_bid.id
     * @param bool $isReadOnlyDb
     * @return AbsenteeBid|null
     */
    public function loadById(?int $absenteeBidId, bool $isReadOnlyDb = false): ?AbsenteeBid
    {
        if (!$absenteeBidId) {
            return null;
        }

        $fn = function () use ($absenteeBidId, $isReadOnlyDb) {
            $repo = $this->prepareRepository($isReadOnlyDb);
            $absenteeBid = $repo
                ->filterId($absenteeBidId)
                ->loadEntity();
            return $absenteeBid;
        };
        $cacheKey = sprintf(Constants\MemoryCache::ABSENTEE_BID_ID, $absenteeBidId);
        $absenteeBid = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $absenteeBid;
    }

    /**
     * Load user's absentee bids in auction
     * @param int|null $auctionId
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return AbsenteeBid[]
     */
    public function loadByAuctionAndUser(?int $auctionId, ?int $userId, bool $isReadOnlyDb = false): array
    {
        if (
            !$auctionId
            || !$userId
        ) {
            return [];
        }

        $absenteeBids = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterUserId($userId)
            ->loadEntities();
        return $absenteeBids;
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return AbsenteeBid[]
     */
    public function loadForAuctionLot(?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): array
    {
        if (
            !$lotItemId
            || !$auctionId
        ) {
            return [];
        }

        $absenteeBidRepository = $this->createAbsenteeBidReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterMaxBidGreater(0)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE);
        $absenteeBids = $absenteeBidRepository->loadEntities();
        return $absenteeBids;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AbsenteeBidReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): AbsenteeBidReadRepository
    {
        $repo = $this->createAbsenteeBidReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId($this->lotStatusIds)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE);
        return $repo;
    }
}
