<?php
/**
 * SAM-6776: Performance improvements for assigning auction lot dates after the CSV upload process
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Date;

use Auction;
use AuctionLotItem;
use DateTime;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Bulk updating lot dates that are taken from the timed scheduled auction. Used instead of
 * AuctionLotDateAssignor::assignForTimed for large set of lot items to increase performance.
 * Performs a direct database update request and notify observers for each changed entity.
 *
 * Class TimedAuctionToLotBulkDateAssignor
 * @package Sam\AuctionLot\Date
 */
class TimedAuctionToLotBulkDateAssignor extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use DbConnectionTrait;

    private const CHUNK_SIZE = 200;

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
    public function construct(): static
    {
        return $this;
    }

    /**
     * @param array $lotItemIds
     * @param Auction $auction
     * @param int $editorUserId
     */
    public function assign(array $lotItemIds, Auction $auction, int $editorUserId): void
    {
        foreach (array_chunk($lotItemIds, self::CHUNK_SIZE) as $ids) {
            //Order matters. Auction lots should contain old values
            $auctionLots = $this->getAuctionLotLoader()->loadByLotItemIds($ids, $auction->Id);
            $this->updateLotDates($ids, $auction->Id, $auction->StartBiddingDate, $auction->StartClosingDate, $editorUserId);
            foreach ($auctionLots as $auctionLot) {
                $this->notifyAuctionLotObservers($auctionLot, $auction->StartBiddingDate, $auction->StartClosingDate);
                unset($auctionLot);
            }
        }
    }

    /**
     * @param array $lotItemIds
     * @param int $auctionId
     * @param DateTime $startBiddingDate
     * @param DateTime $startClosingDate
     * @param int $editorUserId
     */
    protected function updateLotDates(
        array $lotItemIds,
        int $auctionId,
        DateTime $startBiddingDate,
        DateTime $startClosingDate,
        int $editorUserId
    ): void {
        $startBiddingDateIso = $startBiddingDate->format(Constants\Date::ISO);
        $startBiddingDateIsoEscaped = $this->escape($startBiddingDateIso);
        $startClosingDateIso = $startClosingDate->format(Constants\Date::ISO);
        $startClosingDateIsoEscaped = $this->escape($startClosingDateIso);
        $lotItemIdList = implode(',', ArrayCast::castInt($lotItemIds));

        $query = <<<SQL
UPDATE auction_lot_item SET 
start_date = {$startBiddingDateIsoEscaped}, 
start_bidding_date = {$startBiddingDateIsoEscaped}, 
end_date = {$startClosingDateIsoEscaped},
start_closing_date = {$startClosingDateIsoEscaped},
modified_by = {$editorUserId}
WHERE lot_item_id IN({$lotItemIdList})
AND auction_id = {$auctionId}
SQL;
        $this->NonQuery($query);
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param DateTime $startBiddingDate
     * @param DateTime $startClosingDate
     */
    protected function notifyAuctionLotObservers(
        AuctionLotItem $auctionLot,
        DateTime $startBiddingDate,
        DateTime $startClosingDate
    ): void {
        $auctionLot->EndDate = $startClosingDate;
        $auctionLot->StartBiddingDate = $startBiddingDate;
        $auctionLot->StartClosingDate = $startClosingDate;
        $auctionLot->StartDate = $startBiddingDate;
        $auctionLot->notify();
    }
}
