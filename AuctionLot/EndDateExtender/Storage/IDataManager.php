<?php
/**
 * Interface for auction lot end date extending module data Manager
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Apr 04, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\EndDateExtender\Storage;

use DateTimeInterface;
use Sam\Core\Service\CustomizableClassInterface;
use TimedOnlineItem;

/**
 * Interface IDataManager
 * @package Sam\AuctionLot\EndDateExtender\Storage
 */
interface IDataManager extends CustomizableClassInterface
{
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
    public function getTimedItemsForActiveLots(int $auctionId, DateTimeInterface $bidDate, ?int $offset = null, ?int $limit = null): array;

    /**
     * Lock inside transaction timed online items for active lots in auction
     * @param int $auctionId
     * @param DateTimeInterface $bidDate
     */
    public function lockInTransactionTimedItemsForActiveLots(int $auctionId, DateTimeInterface $bidDate): void;

    /**
     * Lock inside transaction auction lot item caches for active lots in auction
     * @param int $auctionId
     * @param DateTimeInterface $bidDate
     */
    public function lockInTransactionAuctionLotCachesForActiveLots(int $auctionId, DateTimeInterface $bidDate): void;

}
