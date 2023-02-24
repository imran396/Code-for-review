<?php
/**
 * SAM-5636  : Refactoring of auction_closer.php - move piecemeal lot updating logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 05, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Closer\BulkGroup\PiecemealLot;

use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class MultiplePiecemealLotCurrentBidUpdater
 * @package Sam\AuctionLot\Closer\BulkGroup\PiecemealLot
 */
class MultiplePiecemealLotCurrentBidUpdater extends CustomizableClass
{
    use AuctionLotCacheUpdaterCreateTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use DbConnectionTrait;
    use SinglePiecemealLotCurrentBidUpdaterCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update piecemeal current bid to max bid
     * @param int $masterAuctionLotId
     * @param int $editorUserId
     * @return void
     */
    public function updateByMasterAuctionLotId(int $masterAuctionLotId, int $editorUserId): void
    {
        $auctionLotCacheManager = $this->createAuctionLotCacheUpdater();
        $repo = $this->createAuctionLotItemReadRepository()
            ->filterByPiecemealRole($masterAuctionLotId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->inlineCondition('bt_cb.bid != bt_cb.max_bid')
            ->joinBidTransactionByCurrentBidFilterBidGreaterOrEqualThanReservePrice()
            ->orderByOrder()
            ->select(
                [
                    'ali.id AS alid',
                    'bt_cb.user_id AS bid_user_id',
                    'bt_cb.max_bid AS max_bid',
                ]
            );
        foreach ($repo->yieldRows() as $row) {
            $this->createSinglePiecemealLotCurrentBidUpdater()
                ->setAuctionLotId($masterAuctionLotId)
                ->setMaxBid($row['max_bid'])
                ->setUserId($row['bid_user_id'])
                ->update($editorUserId);
            $auctionLot = AuctionLotLoader::new()->loadById((int)$row['alid']);
            if (!$auctionLot) {
                log_error(
                    'Available auction lot not found, when set piecemeal current bid to max bid'
                    . composeSuffix(['ali' => $row['alid']])
                );
                return;
            }
            $auctionLotCacheManager->refreshBidInfo($auctionLot, $editorUserId);
        }
    }
}
