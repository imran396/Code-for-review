<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal\Load;

use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Dto\SyncAdminAuctionLotDto;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class AdminDataLoader
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal\Load
 * @internal
 */
class AdminDataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load auction lots data for syncing admin auction lot list page
     *
     * @param array $auctionLotIds
     * @param int $auctionId
     * @param bool $isProfilingEnabled
     * @return SyncAdminAuctionLotDto[]
     */
    public function loadAuctionLotDtos(array $auctionLotIds, int $auctionId, bool $isProfilingEnabled = false): array
    {
        if (!$auctionLotIds) {
            return [];
        }

        /** @noinspection PhpFullyQualifiedNameUsageInspection */
        $lotList = \Sam\Core\Lot\LotList\Search::new();
        $dataSource = $this->prepareAuctionLotDatasource($auctionLotIds, $auctionId);
        $lotList->setDataSource($dataSource);

        $tmpTs = microtime(true);

        $rows = $lotList->getLotListArray();
        $dtos = array_map(
            static function (array $row) {
                return SyncAdminAuctionLotDto::new()->fromDbRow($row);
            },
            $rows
        );
        if ($isProfilingEnabled) {
            log_debug(composeSuffix(['main query' => ((microtime(true) - $tmpTs) * 1000) . 'ms']));
        }
        return $dtos;
    }

    /**
     * Load auction lot order number that uses for hybrid auction lot syncing
     *
     * @param int $auctionId
     * @param int $lotItemId
     * @return int|null
     */
    public function loadAuctionLotOrderNum(int $auctionId, int $lotItemId): ?int
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->select(['`order`'])
            ->filterLotItemId($lotItemId)
            ->filterAuctionId($auctionId)
            ->loadRow();
        return Cast::toInt($row['order'] ?? null);
    }

    /**
     * @param array $auctionLotIds
     * @param int $auctionId
     * @return DataSourceMysql
     */
    protected function prepareAuctionLotDatasource(array $auctionLotIds, int $auctionId): DataSourceMysql
    {
        $resultSetFields = [
            'account_id',
            'alid',
            'asking_bid',
            'auc_tz_location',
            'auction_id',
            'auction_reverse',
            'auction_start_closing_date',
            'auction_status_id',
            'auction_type',
            'bid_count',
            'current_bid',
            'current_bid_placed',
            'current_max_bid',
            'extend_all',
            'extend_time',
            'first_name',
            'hammer_price',
            'high_bidder_company',
            'high_bidder_email',
            'high_bidder_house',
            'high_bidder_user_id',
            'high_bidder_username',
            'id',
            'last_name',
            'lot_en_dt',
            'lot_start_gap_time',
            'lot_status_id',
            'lot_tz_location',
            'lots_per_interval',
            'order_num',
            'reserve_met_notice',
            'reserve_not_met_notice',
            'reserve_price',
            'rtb_current_lot_id',
            'rtb_lot_end_date',
            'rtb_pause_date',
            'seconds_before',
            'seconds_left',
            'stagger_closing',
            'view_count',
            'winner_bidder_num',
            'winner_company',
            'winner_email',
            'winner_user_id',
            'winner_username',
            'winning_auction_id',
        ];
        $dataSource = DataSourceMysql::new()
            ->filterAuctionIds([$auctionId])
            ->filterAuctionLotIds($auctionLotIds)
            ->setResultSetFields($resultSetFields);
        return $dataSource;
    }
}
