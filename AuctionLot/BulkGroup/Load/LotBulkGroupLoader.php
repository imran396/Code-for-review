<?php
/**
 * SAM-5408: Apply LotBulkGroupExistenceChecker
 * https://bidpath.atlassian.net/browse/SAM-5408
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Load;

use AuctionLotItem;
use DateTime;
use Exception;
use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class LotBulkGroupLoader
 * @package Sam\AuctionLot\BulkGroup
 */
class LotBulkGroupLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use LotNoParserCreateTrait;

    /**
     * Class instantiation method
     *
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $lotNoConcatenated
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detectMasterAuctionLotIdByLotNoConcatenated(
        string $lotNoConcatenated,
        int $auctionId,
        bool $isReadOnlyDb = false
    ): ?int {
        if (!$auctionId || $lotNoConcatenated === '') {
            return null;
        }
        $lotNoParsed = $this->createLotNoParser()->construct()->parse($lotNoConcatenated);
        $row = $this->createAuctionLotItemReadRepository()
            ->select(['ali.id'])
            ->joinLotItemFilterActive(true)
            ->filterByMasterRole()
            ->filterAuctionId($auctionId)
            ->filterLotNum($lotNoParsed->lotNum)
            ->filterLotNumExt($lotNoParsed->lotNumExtension)
            ->filterLotNumPrefix($lotNoParsed->lotNumPrefix)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadRow();
        $bulkMasterLotItemId = Cast::toInt($row['id'] ?? null);
        return $bulkMasterLotItemId;
    }

    /**
     * @param int $auctionId
     * @param int[] $skipLotItemIds skip lot items by their ids
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadBulkGroupAllMastersRows(
        int $auctionId,
        array $skipLotItemIds = [],
        bool $isReadOnlyDb = false
    ): array {
        $select = [
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'li.item_num AS item_num',
            'li.item_num_ext AS item_num_ext',
            'li.name AS lot_name',
        ];
        $repo = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterByMasterRole()
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->select($select);

        if ($skipLotItemIds) {
            $repo->skipLotItemId($skipLotItemIds);
        }
        return $repo->loadRows();
    }

    /**
     * Load data array of available piecemeal auction lots for lot bulk group of definite master lot
     * @param int $masterAuctionLotId - bulk master auction lot item id
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadPiecemealLotRows(
        int $masterAuctionLotId,
        bool $isReadOnlyDb = false
    ): array {
        $select = [
            'ali.account_id',
            'ali.auction_id',
            'ali.lot_item_id',
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'alic.seo_url AS lot_seo_url',
            'li.item_num AS item_num',
            'li.item_num_ext AS item_num_ext',
            'li.name AS lot_name',
        ];
        $auctionLotRows = $this
            ->prepareRepositoryForPiecemealAuctionLot($masterAuctionLotId, $isReadOnlyDb)
            ->select($select)
            ->loadRows();
        return $auctionLotRows;
    }

    /**
     * Load available piecemeal auction lots for lot bulk group of definite master lot
     * @param int $masterAuctionLotId - bulk master auction lot item id
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem[]
     */
    public function loadPiecemealAuctionLots(
        int $masterAuctionLotId,
        bool $isReadOnlyDb = false
    ): array {
        $piecemealAuctionLots = $this
            ->prepareRepositoryForPiecemealAuctionLot($masterAuctionLotId, $isReadOnlyDb)
            ->loadEntities();
        return $piecemealAuctionLots;
    }

    /**
     * @param int $masterAuctionLotId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemReadRepository
     */
    protected function prepareRepositoryForPiecemealAuctionLot(
        int $masterAuctionLotId,
        bool $isReadOnlyDb
    ): AuctionLotItemReadRepository {
        return $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterByPiecemealRole($masterAuctionLotId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuctionLotItemCache()
            ->joinLotItemFilterActive(true);
    }

    /**
     * @param int $masterAuctionLotId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function loadBulkGroupSuggestedReserve(int $masterAuctionLotId, bool $isReadOnlyDb = false): float
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterByPiecemealRole($masterAuctionLotId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->select(['SUM(li.reserve_price) AS suggested_reserve'])
            ->loadRow();
        $suggestedReserve = (float)($row['suggested_reserve'] ?? 0.);
        return $suggestedReserve;
    }

    /**
     * @param int $masterAuctionLotId
     * @return float|null
     */
    public function loadBulkGroupTotalWinningBid(int $masterAuctionLotId): ?float
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->filterByPiecemealRole($masterAuctionLotId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinBidTransactionByCurrentBidFilterBidGreaterOrEqualThanReservePrice()
            ->select(['SUM(bt_cb.bid) AS total_winning_bid'])
            ->loadRow();
        $totalWinningBid = (float)($row['total_winning_bid'] ?? 0.);
        return $totalWinningBid;
    }

    /**
     * @param int $masterAuctionLotId
     * @return float|null
     */
    public function loadBulkGroupTotalWinningMaxBid(int $masterAuctionLotId): ?float
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->filterByPiecemealRole($masterAuctionLotId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinBidTransactionByCurrentBidFilterBidGreaterOrEqualThanReservePrice()
            ->select(['SUM(bt_cb.max_bid) AS total_winning_bid'])
            ->loadRow();
        $totalWinningBid = (float)($row['total_winning_bid'] ?? 0.);
        return $totalWinningBid;
    }

    /**
     * TODO: IK, Why do we search for dates only by piecemeal lot dates and don't consider master lot dates here?
     * @param int $masterAuctionLotId
     * @return array
     * @throws Exception
     */
    public function detectDatesForMasterLot(int $masterAuctionLotId): array
    {
        $startDateRow = $this->createAuctionLotItemReadRepository()
            ->filterByPiecemealRole($masterAuctionLotId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuctionLotItemCache()
            ->joinLotItemFilterActive(true)
            ->orderByStartBiddingDate()
            ->select(['ali.start_bidding_date'])
            ->loadRow();

        $startBiddingDateIso = trim($startDateRow['start_bidding_date'] ?? '');
        $startBiddingDateTime = new DateTime($startBiddingDateIso);

        $endDateRow = $this->createAuctionLotItemReadRepository()
            ->filterByPiecemealRole($masterAuctionLotId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuctionLotItemCache()
            ->joinLotItemFilterActive(true)
            ->orderByEndDate(false)
            ->select(['ali.end_date'])
            ->loadRow();

        $endDateIso = trim($endDateRow['end_date'] ?? '');
        $endDate = new DateTime($endDateIso);

        return [
            'start_bidding_date' => $startBiddingDateTime,
            'end_date' => $endDate,
        ];
    }

    /**
     * Load bulk master auction lot item
     * @param int $auctionId
     * @param int $bulkMasterId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadBulkMasterById(int $auctionId, int $bulkMasterId, bool $isReadOnlyDb = false): array
    {
        $select = [
            'ali.account_id',
            'ali.lot_item_id',
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'li.name as lot_name',
        ];
        return $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterId($bulkMasterId)
            ->filterByMasterRole()
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->select($select)
            ->loadRow();
    }
}
