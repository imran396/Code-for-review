<?php
/**
 * Help methods for Bid Increment loading
 *
 * SAM-4474: Modules for Bid Increments
 *
 * @author        Victor Pautoff
 * @since         Oct 15, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\BidIncrement\Load;

use BidIncrement;
use Sam\Bidding\BidIncrement\Validate\BidIncrementExistenceCheckerAwareTrait;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Core\Math\Floating;
use Sam\Storage\ReadRepository\Entity\BidIncrement\BidIncrementReadRepositoryCreateTrait;

/**
 * Class BidIncrementLoader
 * @package Sam\Bidding\BidIncrement\Load
 */
class BidIncrementLoader extends EntityLoaderBase
{
    use BidIncrementReadRepositoryCreateTrait;
    use BidIncrementExistenceCheckerAwareTrait;
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
     * Load BidIncrement by id with memory caching
     * @param int|null $bidIncrementId null id results to null absent entity
     * @param bool $isReadOnlyDb
     * @return BidIncrement|null
     */
    public function load(?int $bidIncrementId, bool $isReadOnlyDb = false): ?BidIncrement
    {
        if (!$bidIncrementId) {
            return null;
        }

        $fn = function () use ($bidIncrementId, $isReadOnlyDb) {
            $bidIncrement = $this->createBidIncrementReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterId($bidIncrementId)
                ->loadEntity();
            return $bidIncrement;
        };

        $cacheKey = sprintf(Constants\MemoryCache::BID_INCREMENT_ID, $bidIncrementId);
        $bidIncrement = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $bidIncrement;
    }

    /**
     * Load all for account and auction type
     * @param int $accountId
     * @param string|string[]|null $auctionType
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param bool $isReadOnlyDb
     * @return BidIncrement[]
     */
    public function loadAll(
        int $accountId,
        string|array|null $auctionType,
        ?int $auctionId = null,
        ?int $lotItemId = null,
        bool $isReadOnlyDb = false
    ): array {
        $bidIncrements = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterAuctionType($auctionType)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->orderByAmount()
            ->loadEntities();
        return $bidIncrements;
    }

    /**
     * Load available with amount lower or equal to $maxRange
     * @param float|null $maxRange
     * @param int|null $accountId
     * @param string|null $auctionType
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param bool $isReadOnlyDb
     * @return BidIncrement|null
     */
    public function loadAvailable(
        ?float $maxRange = null,
        ?int $accountId = null,
        ?string $auctionType = null,
        ?int $auctionId = null,
        ?int $lotItemId = null,
        bool $isReadOnlyDb = false
    ): ?BidIncrement {
        $maxRange = (float)$maxRange;
        if (Floating::lt($maxRange, 0)) {
            log_error("Max Range cannot be negative" . composeSuffix(['maxRange' => $maxRange]));
            return null;
        }
        if (
            $lotItemId
            && $this->getBidIncrementExistenceChecker()->existForLot($lotItemId, $isReadOnlyDb)
        ) {
            $bidIncrement = $this->loadAvailableForLot($lotItemId, $maxRange, $isReadOnlyDb);
        } elseif (
            $auctionId
            && $this->getBidIncrementExistenceChecker()->existForAuction($auctionId, $isReadOnlyDb)
        ) {
            $bidIncrement = $this->loadAvailableForAuction($auctionId, $maxRange, $isReadOnlyDb);
        } else {
            $accountId = (int)$accountId;
            if ($accountId <= 0) {
                log_error("Account Id should be positive" . composeSuffix(['acc' => $accountId]));
                return null;
            }
            $auctionType = Cast::toString($auctionType, Constants\Auction::AUCTION_TYPES);
            if (!$auctionType) {
                log_error('Auction type incorrect' . composeSuffix(['type' => $auctionType]));
                return null;
            }
            $bidIncrement = $this->loadAvailableForAccount($accountId, $auctionType, $maxRange, $isReadOnlyDb);
        }

        return $bidIncrement;
    }

    /**
     * Load available for account and auction type with amount lower or equal to $maxRange
     * @param int $accountId
     * @param string $auctionType
     * @param float $maxRange
     * @param bool $isReadOnlyDb
     * @return BidIncrement|null
     */
    public function loadAvailableForAccount(
        int $accountId,
        string $auctionType,
        float $maxRange,
        bool $isReadOnlyDb = false
    ): ?BidIncrement {
        $bidIncrement = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterAmountLessOrEqual($maxRange)
            ->filterAuctionId(null)
            ->filterAuctionType($auctionType)
            ->filterLotItemId(null)
            ->orderByAmount(false)
            ->loadEntity();
        return $bidIncrement;
    }

    /**
     * Load available for auction
     * @param int $auctionId
     * @param float|null $maxRange
     * @param bool $isReadOnlyDb
     * @return BidIncrement|null
     */
    public function loadAvailableForAuction(
        int $auctionId,
        ?float $maxRange = null,
        bool $isReadOnlyDb = false
    ): ?BidIncrement {
        $bidIncrement = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAmountLessOrEqual($maxRange)
            ->filterAuctionId($auctionId)
            ->filterAuctionType(null)
            ->filterLotItemId(null)
            ->orderByAmount(false)
            ->loadEntity();
        return $bidIncrement;
    }

    /**
     * Load available for lot
     * @param int $lotItemId
     * @param float $maxRange
     * @param bool $isReadOnlyDb
     * @return BidIncrement|null
     */
    public function loadAvailableForLot(
        int $lotItemId,
        float $maxRange,
        bool $isReadOnlyDb = false
    ): ?BidIncrement {
        $bidIncrement = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAmountLessOrEqual($maxRange)
            ->filterAuctionId(null)
            ->filterAuctionType(null)
            ->filterLotItemId($lotItemId)
            ->orderByAmount(false)
            ->loadEntity();
        return $bidIncrement;
    }

    /**
     * Load by amount for account
     * @param float $amount
     * @param int $accountId
     * @param string $auctionType
     * @param bool $isReadOnlyDb
     * @return BidIncrement|null
     */
    public function loadByAmountForAccount(
        float $amount,
        int $accountId,
        string $auctionType,
        bool $isReadOnlyDb = false
    ): ?BidIncrement {
        $bidIncrement = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterAmount($amount)
            ->filterAuctionType($auctionType)
            ->filterAuctionId(null)
            ->filterLotItemId(null)
            ->loadEntity();
        return $bidIncrement;
    }

    /**
     * Load for auctions
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return BidIncrement[]
     */
    public function loadForAuction(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $bidIncrements = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->orderByAmount()
            ->loadEntities();
        return $bidIncrements;
    }

    /**
     * Load for lots
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return BidIncrement[]
     */
    public function loadForLot(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        $bidIncrements = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->orderByAmount()
            ->loadEntities();
        return $bidIncrements;
    }
}
