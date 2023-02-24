<?php
/**
 * SAM-6377 : Separate bulk group related logic to classes
 * https://bidpath.atlassian.net/browse/SAM-6377
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Save\Date;

use AuctionLotItem;
use Sam\AuctionLot\BulkGroup\Load\LotBulkGroupLoaderAwareTrait;
use Sam\AuctionLot\BulkGroup\Validate\LotBulkGroupExistenceCheckerAwareTrait;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BulkGroupLotDateUpdater
 * @package Sam\AuctionLot\BulkGroup
 */
class LotBulkGroupLotDateUpdater extends CustomizableClass
{
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use LotBulkGroupExistenceCheckerAwareTrait;
    use LotBulkGroupLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Refresh date values in cache for timed auction lot item
     *
     * @param AuctionLotItem|null $masterAuctionLot null means no action
     * @param int $editorUserId
     * @throws \Exception
     */
    public function updateByMasterAuctionLot(?AuctionLotItem $masterAuctionLot, int $editorUserId): void
    {
        if (!$masterAuctionLot) {
            return;
        }

        if ($this->getLotBulkGroupExistenceChecker()->existPiecemealGrouping($masterAuctionLot->Id)) {
            $values = $this->getLotBulkGroupLoader()->detectDatesForMasterLot($masterAuctionLot->Id);
            log_debug('Updating bulk master start and end date: ' . var_export($values, true));
            $lotDates = TimedAuctionLotDates::new()
                ->setStartBiddingDate($values['start_bidding_date'])
                ->setStartClosingDate($values['end_date']);

            $this->createAuctionLotDateAssignor()->assignForTimed($masterAuctionLot, $lotDates, $editorUserId);
        }
    }

    /**
     * Refresh date values in cache for timed auction lot item
     * @param int|null $auctionLotId
     * @param int $editorUserId
     * @throws \Exception
     */
    public function updateByMasterAuctionLotId(?int $auctionLotId, int $editorUserId): void
    {
        $masterAuctionLot = $this->getAuctionLotLoader()->loadById($auctionLotId);
        $this->updateByMasterAuctionLot($masterAuctionLot, $editorUserId);
    }

    /**
     * Refresh date values in cache for master auction lot of lot bulk group where passed piecemeal lot is member
     * @param AuctionLotItem|null $piecemealAuctionLot
     * @param int $editorUserId
     * @throws \Exception
     */
    public function updateByPiecemealAuctionLot(?AuctionLotItem $piecemealAuctionLot, int $editorUserId): void
    {
        if (!$piecemealAuctionLot) {
            return;
        }

        $this->updateByMasterAuctionLotId($piecemealAuctionLot->BulkMasterId, $editorUserId);
    }
}
