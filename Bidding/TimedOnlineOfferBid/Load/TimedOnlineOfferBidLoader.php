<?php
/**
 * SAM-4745: TimedOnlineOfferBid loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-31
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\TimedOnlineOfferBid\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidReadRepository;
use Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidReadRepositoryCreateTrait;
use TimedOnlineOfferBid;

/**
 * Class TimedOnlineOfferBidLoader
 * @package Sam\Bidding\TimedOnlineOfferBid\Load
 */
class TimedOnlineOfferBidLoader extends EntityLoaderBase
{
    use TimedOnlineOfferBidReadRepositoryCreateTrait;

    // Filter results by these statuses
    /** @var bool|null */
    protected ?bool $filterAccountActive = null;
    /** @var int[] */
    protected array $filterAuctionStatusId = [];
    /** @var bool|null */
    protected ?bool $filterLotItemActive = null;
    /** @var int[] */
    protected array $filterLotStatusId = [];

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
        $this->filterAccountActive(true);
        $this->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses);
        $this->filterLotItemActive(true);
        $this->filterLotStatusId(Constants\Lot::$availableLotStatuses);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->dropFilterAccountActive();
        $this->dropFilterAuctionStatusId();
        $this->dropFilterLotItemActive();
        $this->dropFilterLotStatusId();
        return $this;
    }

    /**
     * Load a TimedOnlineOfferBid from PK Info
     * @param int|null $id null leads to null result
     * @param bool $isReadOnlyDb
     * @return TimedOnlineOfferBid|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?TimedOnlineOfferBid
    {
        $id = Cast::toInt($id, Constants\Type::F_INT_POSITIVE);
        if (!$id) {
            return null;
        }
        $timedOfferBid = $this->prepareRepository($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        return $timedOfferBid;
    }

    /**
     * @param int|null $userId null for anonymous user
     * @param int $auctionLotId
     * @param bool $isCounterBid
     * @param bool $isReadOnlyDb
     * @return TimedOnlineOfferBid|null
     */
    public function loadByUserAndAuctionLotAndCounterBid(
        ?int $userId,
        int $auctionLotId,
        bool $isCounterBid = false,
        bool $isReadOnlyDb = false
    ): ?TimedOnlineOfferBid {
        if (!$userId || !$auctionLotId) {
            return null;
        }
        $timedOfferBid = $this->prepareRepository($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAuctionLotItemId($auctionLotId)
            ->filterIsCounterBid($isCounterBid)
            ->loadEntity();
        return $timedOfferBid;
    }

    /**
     * @param int|null $userId null for anonymous user
     * @param int $auctionLotId
     * @param bool $isReadOnlyDb
     * @return TimedOnlineOfferBid|null
     */
    public function loadNewUserCounterOfferBid(?int $userId, int $auctionLotId, bool $isReadOnlyDb = false): ?TimedOnlineOfferBid
    {
        if (!$userId || !$auctionLotId) {
            return null;
        }
        $timedOfferBid = $this->prepareRepository($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAuctionLotItemId($auctionLotId)
            ->filterIsCounterBid(true)
            ->filterStatus(Constants\TimedOnlineOfferBid::STATUS_NONE)
            ->loadEntity();
        return $timedOfferBid;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return TimedOnlineOfferBidReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): TimedOnlineOfferBidReadRepository
    {
        $repo = $this->createTimedOnlineOfferBidReadRepository()
            ->filterDeleted(false)
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->filterAccountActive !== null) {
            $repo->joinAccountFilterActive($this->filterAccountActive);
        }
        if ($this->filterLotItemActive !== null) {
            $repo->joinLotItemFilterActive($this->filterLotItemActive);
        }
        if ($this->filterAuctionStatusId) {
            $repo->joinAuctionFilterAuctionStatusId($this->filterAuctionStatusId);
        }
        if ($this->filterLotStatusId) {
            $repo->joinAuctionLotItemFilterLotStatusId($this->filterLotStatusId);
        }
        return $repo;
    }

    /**
     * Define filtering by ali.lot_status_id
     * @param int[] $lotStatusIds
     * @return static
     */
    public function filterLotStatusId(array $lotStatusIds): static
    {
        $this->filterLotStatusId = $lotStatusIds;
        return $this;
    }

    /**
     * Drop filtering by ali.lot_status_id
     * @return static
     */
    public function dropFilterLotStatusId(): static
    {
        $this->filterLotStatusId = [];
        return $this;
    }

    /**
     * Define filtering by a.auction_status_id
     * @param int[] $auctionStatusIds
     * @return static
     */
    public function filterAuctionStatusId(array $auctionStatusIds): static
    {
        $this->filterAuctionStatusId = $auctionStatusIds;
        return $this;
    }

    /**
     * Drop filtering by a.auction_status_id
     * @return static
     */
    public function dropFilterAuctionStatusId(): static
    {
        $this->filterAuctionStatusId = [];
        return $this;
    }


    /**
     * @param bool $isActive
     * @return static
     */
    public function filterLotItemActive(bool $isActive): static
    {
        $this->filterLotItemActive = $isActive;
        return $this;
    }

    /**
     * @return static
     */
    public function dropFilterLotItemActive(): static
    {
        $this->filterLotItemActive = null;
        return $this;
    }

    /**
     * @param bool $isActive
     * @return static
     */
    public function filterAccountActive(bool $isActive): static
    {
        $this->filterAccountActive = $isActive;
        return $this;
    }

    /**
     * @return static
     */
    public function dropFilterAccountActive(): static
    {
        $this->filterAccountActive = null;
        return $this;
    }
}
