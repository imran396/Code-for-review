<?php
/**
 * SAM-5154: Positional auction lot loader
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           09.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\AuctionLot\Load;

use AuctionLotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Order\Query\AuctionLotOrderMysqlQueryBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class PositionalAuctionLotLoader
 * @package Sam\AuctionLot\Load
 */
class PositionalAuctionLotLoader extends EntityLoaderBase
{
    use AuctionLoaderAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionLotOrderMysqlQueryBuilderCreateTrait;
    use LotItemLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get the first lot item(lowest lot_num) loaded
     * in an auction based on the parameter auction id.
     *
     * @param int $auctionId
     * @param int[] $lotStatuses
     * @return AuctionLotItem|null
     */
    public function loadFirstLot(int $auctionId, array $lotStatuses = null): ?AuctionLotItem
    {
        $lotStatuses = $lotStatuses ?? Constants\Lot::$availableLotStatuses;
        $auctionLot = AuctionLotItemReadRepository::new()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId($lotStatuses)
            ->joinLotItemFilterActive(true)
            ->orderByOrder()
            ->orderByLotNumPrefix()
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->loadEntity();
        return $auctionLot;
    }

    /**
     * Get the last lot item(highest lot_num) loaded
     * in an auction based on the parameter auction id.
     *
     * @param int $auctionId
     * @return AuctionLotItem|null
     */
    public function loadLastLot(int $auctionId): ?AuctionLotItem
    {
        $auctionLot = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->orderByOrder(false)
            ->orderByLotNumPrefix(false)
            ->orderByLotNum(false)
            ->orderByLotNumExt(false)
            ->loadEntity();
        return $auctionLot;
    }

    /**
     * Get previous AuctionLotItem object
     *
     * @param int $auctionId
     * @param int|null $currentLotItemId current lot item id, null leads to load the first lot of the current auction
     * @param array $options = [
     *  'lotStatuses' => int[], // filtering lot statuses for target lots (
     *  'skipUpcoming' => bool, // avoid upcoming lots (def: false)
     *  'onlyOngoing' => bool, // show only ongoing lots (def: false)
     * ]
     * @return AuctionLotItem|null
     */
    public function loadPreviousLot(
        int $auctionId,
        ?int $currentLotItemId = null,
        array $options = []
    ): ?AuctionLotItem {
        if (!$currentLotItemId) {
            return $this->loadFirstLot($auctionId);
        }
        $dbResult = $this->loadPreviousLots($auctionId, $currentLotItemId, $options);
        $auctionLot = current($dbResult);
        return $auctionLot instanceof AuctionLotItem ? $auctionLot : null;
    }

    /**
     * Get next AuctionLotItem object
     * @param int $auctionId
     * @param int|null $currentLotItemId current lot item id, null leads to load the first lot of the current auction
     * @param bool $shouldNextUnsold only look for unsold; default: false TODO: IK, 2020-08, It seems, $shouldNextUnsold argument is excess and can be removed and replaced with respective values for other options
     * @param array $options = [
     *  'lotStatuses' => int[], // filtering target lot statuses (def: Constants\Lot::$availableLotStatuses)
     *  'skipUpcoming' => bool, // avoid upcoming lots (def: false)
     *  'onlyOngoing' => bool, // show only ongoing lots (def: false)
     *  'sourceLotStatuses' => int[], filtering lot statuses for source of search (def: Constants\Lot::$availableLotStatuses)
     *  'offset' => int, // fetching offset (def: null)
     *  'limit' => int, // limit result lot count (def: 1)
     * ]
     * @return AuctionLotItem|null
     */
    public function loadNextLot(
        int $auctionId,
        ?int $currentLotItemId = null,
        bool $shouldNextUnsold = false,
        array $options = []
    ): ?AuctionLotItem {
        $options['lotStatuses'] = $options['lotStatuses'] ?? [Constants\Lot::LS_ACTIVE, Constants\Lot::LS_UNSOLD];

        if (!$currentLotItemId) {
            if ($shouldNextUnsold) {
                $dbResult = $this->loadUnsoldLots($auctionId, 1, $options['lotStatuses']);
                return current($dbResult) ?: null;
            }
            return $this->loadFirstLot($auctionId);
        }

        if ($shouldNextUnsold) {
            $dbResult = $this->loadNextLots($auctionId, $currentLotItemId, $options);
            return current($dbResult) ?: null;
        }

        $options['lotStatuses'] = Constants\Lot::$availableLotStatuses;
        $dbResult = $this->loadNextLots($auctionId, $currentLotItemId, $options);
        return current($dbResult) ?: null;
    }

    /**
     * Get array of previous AuctionLotItem objects in auction.
     * It gives second try to load previous lots with consideration of the fact,
     * that source lot may be deleted at the moment of searching.
     *
     * @param int $auctionId current auction id
     * @param int $currentLotItemId current lot item id
     * @param array $options = [
     *  'lotStatuses' => int[], // filtering lot statuses (def: Constants\Lot::$availableLotStatuses)
     *  'skipUpcoming' => bool, // avoid upcoming lots (def: false)
     *  'onlyOngoing' => bool, // show only ongoing lots (def: false)
     *  'limit' => int, // result lot count limit (def: 1)
     * ]
     * @return AuctionLotItem[]
     */
    public function loadPreviousLots(
        int $auctionId,
        int $currentLotItemId,
        array $options = []
    ): array {
        // First search among available statuses for source lot
        $auctionLots = $this->loadPreviousLotsConsideringSourceLotStatus($auctionId, $currentLotItemId, $options);
        if (!$auctionLots) {
            // Give a try without filtering by statuses for source lot, so we could catch deleted auction_lot_item records
            $options['sourceLotStatuses'] = [];
            $auctionLots = $this->loadPreviousLotsConsideringSourceLotStatus($auctionId, $currentLotItemId, $options);
            if ($auctionLots) {
                log_debug(
                    'Source auction lot is not more available, we need to refer to soft-deleted entry for loading previous lots'
                    . composeSuffix(array_merge(['li' => $currentLotItemId, 'a' => $auctionId], $options))
                );
            }
        }
        return $auctionLots;
    }

    /**
     * Get array of previous AuctionLotItem objects in auction
     *
     * @param int $auctionId
     * @param int $currentLotItemId current lot item id
     * @param array $options = [
     *  'lotStatuses' => int[], // filtering lot statuses for target lots (def: Constants\Lot::$availableLotStatuses)
     *  'skipUpcoming' => bool, // avoid upcoming lots (def: false)
     *  'onlyOngoing' => bool, // show only ongoing lots (def: false)
     *  'limit' => int, // result lot count limit (def: 1)
     *  'sourceLotStatuses' => int[], filtering lot statuses for source of search (def: Constants\Lot::$availableLotStatuses)
     * ]
     * @return AuctionLotItem[]
     */
    protected function loadPreviousLotsConsideringSourceLotStatus(
        int $auctionId,
        int $currentLotItemId,
        array $options = []
    ): array {
        $repo = $this->initRepository($auctionId, $currentLotItemId, $options);
        if (!$repo) {
            return [];
        }
        $prevLotsCondition = $this->createAuctionLotOrderMysqlQueryBuilder()->buildPrevLotsWhereClause();
        $auctionLots = $repo
            ->inlineCondition($prevLotsCondition)
            ->orderByOrderAndLotFullNumber(false)
            ->loadEntities();
        return $auctionLots;
    }

    /**
     * Get array of next AuctionLotItem objects in auction
     * It gives second try to load next lots with consideration of the fact,
     * that source lot may be deleted at the moment of searching.
     *
     * @param int $auctionId current auction id
     * @param int|null $currentLotItemId current lot item id, null means there is no filtering by lot item id when initializing repository
     * @param array $options = [
     *  'lotStatuses' => int[], // filtering target lot statuses (def: Constants\Lot::$availableLotStatuses)
     *  'skipUpcoming' => bool, // avoid upcoming lots (def: false)
     *  'onlyOngoing' => bool, // show only ongoing lots (def: false)
     *  'offset' => int, // fetching offset (def: null)
     *  'limit' => int, // limit result lot count (def: 1)
     * ]
     * @return AuctionLotItem[]
     */
    public function loadNextLots(int $auctionId, ?int $currentLotItemId, array $options = []): array
    {
        // First search among available statuses for source lot
        $auctionLots = $this->loadNextLotsConsideringSourceLotStatus($auctionId, $currentLotItemId, $options);
        if (!$auctionLots) {
            // Give a try without filtering by statuses for source lot, so we could catch deleted auction_lot_item records
            $options['sourceLotStatuses'] = [];
            $auctionLots = $this->loadNextLotsConsideringSourceLotStatus($auctionId, $currentLotItemId, $options);
            if ($auctionLots) {
                log_debug(
                    'Source auction lot is not more available, we need to refer to soft-deleted entry for loading next lots'
                    . composeSuffix(array_merge(['li' => $currentLotItemId, 'a' => $auctionId], $options))
                );
            }
        }
        return $auctionLots;
    }

    /**
     * Get array of next AuctionLotItem objects in auction
     *
     * @param int $auctionId
     * @param int|null $currentLotItemId current lot item id, null means there is no filtering by lot item id when initializing repository
     * @param array $options = [
     *  'lotStatuses' => int[], // filtering target lot statuses (def: Constants\Lot::$availableLotStatuses)
     *  'skipUpcoming' => bool, // avoid upcoming lots (def: false)
     *  'onlyOngoing' => bool, // show only ongoing lots (def: false)
     *  'offset' => int, // fetching offset (def: null)
     *  'limit' => int, // limit result lot count (def: 1)
     * ]
     * @return AuctionLotItem[]
     */
    public function loadNextLotsConsideringSourceLotStatus(int $auctionId, ?int $currentLotItemId, array $options): array
    {
        $repo = $this->initRepository($auctionId, $currentLotItemId, $options);
        if (!$repo) {
            return [];
        }
        $nextLotsCondition = $this->createAuctionLotOrderMysqlQueryBuilder()->buildNextLotsWhereClause();
        $auctionLots = $repo
            ->inlineCondition($nextLotsCondition)
            ->orderByOrderAndLotFullNumber()
            ->loadEntities();
        return $auctionLots;
    }

    /**
     * Get array of unsold AuctionLotItem objects in auction
     *
     * @param int $auctionId
     * @param int $limit number of lots after this; default 1
     * @param array|null $lotStatuses null means there is no filtering by lot statuses
     * @return AuctionLotItem[]
     */
    protected function loadUnsoldLots(int $auctionId, int $limit = 1, array $lotStatuses = null): array
    {
        $lotStatuses = $lotStatuses ?? [Constants\Lot::LS_ACTIVE, Constants\Lot::LS_UNSOLD];
        $auctionLots = $this->createAuctionLotItemReadRepository()
            ->joinLotItemFilterActive(true)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId($lotStatuses)
            ->orderByOrder()
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->orderByLotNumPrefix()
            ->limit($limit)
            ->loadEntities();
        return $auctionLots;
    }

    /**
     * @param int|null $auctionId
     * @return AuctionLotItem|null
     */
    public function loadLastSoldAuctionLot(?int $auctionId): ?AuctionLotItem
    {
        if (!$auctionId) {
            return null;
        }

        $auctionLot = $this->createAuctionLotItemReadRepository()
            ->joinLotItemFilterActive(true)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::LS_SOLD)
            ->joinLotItemOrderByDateSold(false)
            ->joinLotItemOrderById(false)
            ->limit(1)
            ->loadEntity();
        return $auctionLot;
    }

    /**
     * Initialize repository with common parameters used by other loader methods
     *
     * @param int $auctionId
     * @param int|null $currentLotItemId null means there is no filtering by lot item id
     * @param array $options = [
     *  'lotStatuses' => int[], // filtering target lot statuses (def: Constants\Lot::$availableLotStatuses)
     *  'skipUpcoming' => bool, // avoid upcoming lots (def: false)
     *  'onlyOngoing' => bool, // show only ongoing lots (def: false)
     *  'sourceLotStatuses' => int[], filtering lot statuses for source of search (def: Constants\Lot::$availableLotStatuses)
     *  'offset' => int, // fetching offset (def: null)
     *  'limit' => int, // limit result lot count (def: 1)
     * ]
     * @return AuctionLotItemReadRepository|null
     */
    protected function initRepository(
        int $auctionId,
        ?int $currentLotItemId,
        array $options = []
    ): ?AuctionLotItemReadRepository {
        $isSkipUpcoming = $options['skipUpcoming'] ?? false;
        $isOnlyOngoing = $options['onlyOngoing'] ?? false;
        $targetLotStatuses = $options['lotStatuses'] ?? Constants\Lot::$availableLotStatuses;
        $sourceLotStatuses = $options['sourceLotStatuses'] ?? Constants\Lot::$availableLotStatuses;
        $offset = $options['offset'] ?? null;
        $limit = $options['limit'] ?? 1;

        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                'Available auction not found for sample lot loading'
                . composeSuffix(['a' => $auctionId])
            );
            return null;
        }

        $repo = $this->createAuctionLotItemReadRepository()
            ->joinAuction()
            ->joinLotItemFilterActive(true)
            ->joinAuctionLotItemCache()
            ->joinAuctionLotItemFilterLotItemId($currentLotItemId)// it joins with ali2 for source lot
            ->filterAuctionId($auction->Id)
            ->filterLotStatusId($targetLotStatuses)
            ->offset($offset)
            ->limit($limit);

        if (
            $isSkipUpcoming
            && $auction->isTimed()
            && $auction->NotShowUpcomingLots
        ) {
            $repo->filterTimedLotStartDateInPast();
        } elseif (
            $isOnlyOngoing
            && $auction->OnlyOngoingLots
        ) {
            $repo->filterLotStatusId(Constants\Lot::LS_ACTIVE);
            if ($auction->isTimed()) {
                // ad required for filterTimedLotEndDateInFuture
                $repo
                    ->joinAuctionDynamic()
                    ->filterTimedLotEndDateInFuture();
            }
        }

        if ($sourceLotStatuses) {
            $repo->inlineCondition("ali2.lot_status_id IN (" . implode(',', $sourceLotStatuses) . ")");
        }

        return $repo;
    }
}
