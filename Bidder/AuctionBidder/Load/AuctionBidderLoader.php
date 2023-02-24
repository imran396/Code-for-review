<?php
/**
 * SAM-5338: Auction bidder loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Load;

use AuctionBidder;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\EntityLoader\AuctionBidderAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class AuctionBidderLoader
 * @package Sam\Bidder\AuctionBidder\Load\AuctionBidderLoader
 */
class AuctionBidderLoader extends EntityLoaderBase
{
    use AuctionBidderAllFilterTrait;
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
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * Get AuctionBidder object by auction_bidder.user_id, auction_bidder.auction_id
     * Memory cache used.
     *
     * @param int|null $userId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionBidder|null
     */
    public function load(?int $userId, ?int $auctionId, bool $isReadOnlyDb = false): ?AuctionBidder
    {
        if (
            !$userId
            || !$auctionId
        ) {
            return null;
        }

        $fn = function () use ($userId, $auctionId, $isReadOnlyDb) {
            $auctionBidder = $this->prepareAuctionBidderRepository($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->filterUserId($userId)
                ->loadEntity();
            return $auctionBidder;
        };

        return $this->loadFromCache(Constants\MemoryCache::AUCTION_BIDDER_USER_ID_AUCTION_ID, [$userId, $auctionId], $fn);
    }

    /**
     * Load selected fields from AuctionBidder record identified by user and auction.
     * @param array $select
     * @param int|null $userId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelected(array $select, ?int $userId, ?int $auctionId, bool $isReadOnlyDb = false): array
    {
        if (
            !$userId
            || !$auctionId
        ) {
            return [];
        }

        return $this->prepareAuctionBidderRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterUserId($userId)
            ->select($select)
            ->loadRow();
    }

    /**
     * Get AuctionBidder object by auction_bidder.bidder_num, auction_bidder.auction_id
     * Memory cache used.
     *
     * @param string $bidderNum
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionBidder|null
     */
    public function loadByBidderNum(string $bidderNum, ?int $auctionId, bool $isReadOnlyDb = false): ?AuctionBidder
    {
        $auctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        if (!$auctionId) {
            return null;
        }

        $fn = function () use ($bidderNum, $auctionId, $isReadOnlyDb) {
            $auctionBidder = $this->prepareAuctionBidderRepository($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->filterBidderNum($bidderNum)
                ->loadEntity();
            return $auctionBidder;
        };

        return $this->loadFromCache(Constants\MemoryCache::AUCTION_BIDDER_BIDDER_NUM_AUCTION_ID, [$bidderNum, $auctionId], $fn);
    }

    /**
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return AuctionBidder|null
     */
    public function loadById(?int $id, bool $isReadOnlyDb = false): ?AuctionBidder
    {
        if (!$id) {
            return null;
        }

        $auctionBidder = $this->prepareAuctionBidderRepository($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        return $auctionBidder;
    }

    /**
     * Returns an array with all active (live & upcoming) auctions that a user is approved for
     *
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return AuctionBidder[]
     */
    public function loadOpenAuctionsApprovedIn(?int $userId, bool $isReadOnlyDb = false): array
    {
        if (!$userId) {
            return [];
        }
        $auctionBidders = $this->prepareAuctionBidderRepository($isReadOnlyDb)
            ->filterApproved(true)
            ->filterUserId($userId)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$openAuctionStatuses)
            ->loadEntities();
        return $auctionBidders;
    }

    /**
     * @param string $key
     * @param array<int|string> $params
     * @param callable $fn
     * @return AuctionBidder|null
     */
    protected function loadFromCache(string $key, array $params, callable $fn): ?AuctionBidder
    {
        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey($key, $params);
        $filterDescriptors = $this->collectFilterDescriptors();
        $auctionBidder = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $auctionBidder;
    }
}
