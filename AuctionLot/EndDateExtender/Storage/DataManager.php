<?php
/**
 * Db data Manager for auction lot end date extending module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Apr 04, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Note: if we will need the same functionality outside of EndDateExtender module,
 * we could extract this code to some more generic location (E.g. \Sam\TimedItem\Storage\DataManager),
 * but we still will support current interface \Sam\AuctionLot\EndDateExtender\Storage\IDataManager
 *
 * Important: As soon as we have implemented SAM-2298 (operating with auction dates instead of lot dates, when "Extend All" option is On)
 * we don't need current functionality anymore.. So probably, we will need to remove unused code.
 *
 */

namespace Sam\AuctionLot\EndDateExtender\Storage;

use DateTimeInterface;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use TimedOnlineItem;

/**
 * Class DataManager
 * @package Sam\AuctionLot\EndDateExtender\Storage
 */
class DataManager extends CustomizableClass implements IDataManager
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return array of timed online items of active auction lots.
     * Consider that ending date should be in future (do not consider active lots, which will be closed in the near bin/cron/auction_closer.php session)
     * We can split processed entries by chunks, possibly it will be useful with big quantity of lots
     * @param int $auctionId
     * @param DateTimeInterface $bidDate Date/time, when bid was placed
     * @param int|null $offset
     * @param int|null $limit
     * @return TimedOnlineItem[]
     */
    public function getTimedItemsForActiveLots(int $auctionId, DateTimeInterface $bidDate, ?int $offset = null, ?int $limit = null): array
    {
        $qBidDate = $bidDate->format(Constants\Date::ISO);
        $limitQuery = '';
        $n = "\n";
        if (
            $offset
            || $limit
        ) {
            $limitQuery = " LIMIT " . (int)$offset . ", " . (int)$limit;
        }
        // @formatter:off
        $query =
            "SELECT toi.* ". $n .
            "FROM timed_online_item toi " . $n .
            "INNER JOIN auction_lot_item ali " . $n .
                "ON ali.lot_item_id = toi.lot_item_id " . $n .
                "AND ali.auction_id = " . $this->escape($auctionId) . " " . $n .
                "AND ali.lot_status_id = " . Constants\Lot::LS_ACTIVE . " " . $n .
            "INNER JOIN lot_item li " . $n .
                "ON toi.lot_item_id = li.id " . $n .
                "AND li.active " . $n .
            "INNER JOIN auction_lot_item_cache alic " . $n .
                "ON alic.auction_lot_item_id = ali.id " . $n .
            "WHERE " . $n .
                "alic.start_date <= " . $this->escape($qBidDate) . " " . $n . // the lot is started
                "AND alic.end_date >= " . $this->escape($qBidDate) . $n .     // and the lot end time is in the future
            $limitQuery;
        // @formatter:on
        $dbResult = $this->query($query);
        $timed = TimedOnlineItem::InstantiateDbResult($dbResult);
        return $timed;
    }

    /**
     * Lock inside transaction timed online items for active lots in auction
     * @param int $auctionId
     * @param DateTimeInterface $bidDate
     */
    public function lockInTransactionTimedItemsForActiveLots(int $auctionId, DateTimeInterface $bidDate): void
    {
        $qBidDate = $bidDate->format(Constants\Date::ISO);
        $lotActive = Constants\Lot::LS_ACTIVE;
        $query = <<<SQL
        SELECT toi.modified_on FROM timed_online_item AS toi 
        INNER JOIN auction_lot_item AS ali ON ali.lot_item_id = toi.lot_item_id
          AND ali.auction_id = "{$this->escape($auctionId)}"
          AND ali.lot_status_id = "{$lotActive}"
        INNER JOIN lot_item AS li ON toi.lot_item_id = li.id
          AND li.active
        INNER JOIN auction_lot_item_cache AS alic ON ali.id = alic.auction_lot_item_id
        WHERE alic.start_date <= "{$this->escape($qBidDate)}"  
          AND alic.end_date >= "{$this->escape($qBidDate)}"   
        FOR UPDATE  
SQL;
        $this->nonQuery($query);
    }

    /**
     * Lock inside transaction auction lot item caches for active lots in auction
     * @param int $auctionId
     * @param DateTimeInterface $bidDate
     */
    public function lockInTransactionAuctionLotCachesForActiveLots(int $auctionId, DateTimeInterface $bidDate): void
    {
        $qBidDate = $bidDate->format(Constants\Date::ISO);
        $lotActive = Constants\Lot::LS_ACTIVE;
        $query = <<<SQL
        SELECT alic.current_bid FROM auction_lot_item_cache AS alic 
        INNER JOIN auction_lot_item AS ali ON alic.auction_lot_item_id = ali.id
          AND ali.auction_id = "{$this->escape($auctionId)}"
          AND ali.lot_status_id = "{$lotActive}"
        INNER JOIN lot_item AS li ON ali.lot_item_id = li.id
          AND li.active
        WHERE alic.start_date <= "{$this->escape($qBidDate)}"  
          AND alic.end_date >= "{$this->escape($qBidDate)}"   
        FOR UPDATE  
SQL;
        $this->nonQuery($query);
    }
}
