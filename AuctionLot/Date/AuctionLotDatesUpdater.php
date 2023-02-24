<?php
/**
 * SAM-6079: Implement lot start closing date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Date;

use Auction;
use AuctionLotItem;
use DateInterval;
use Generator;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\BulkGroup\Validate\LotBulkGroupExistenceCheckerAwareTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\AuctionLot\Validate\TimedItemExistenceCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Populates auction lot dates if these dates depends on auction dates.
 * Should be used after changing auction dates
 *
 * Class AuctionLotDatesUpdater
 * @package Sam\AuctionLot\Date
 */
class AuctionLotDatesUpdater extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use LotBulkGroupExistenceCheckerAwareTrait;
    use StaggerClosingHelperCreateTrait;
    use TimedItemExistenceCheckerAwareTrait;
    use TimedItemLoaderAwareTrait;

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
     * @param int $auctionId Auction ID whose lots need to be updated
     * @param int $editorUserId
     */
    public function update(int $auctionId, int $editorUserId): void
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error('Available auction not found' . composeSuffix(['a' => $auctionId]));
            return;
        }

        if ($auction->isTimed()) {
            $this->updateForTimedAuction($auction, $editorUserId);
            return;
        }

        if ($auction->isHybrid()) {
            $this->updateForHybridAuction($auction, $editorUserId);
            return;
        }
    }

    /**
     * @param Auction $auction
     * @param int $editorUserId
     */
    private function updateForHybridAuction(Auction $auction, int $editorUserId): void
    {
        $auctionLotGenerator = $this->yieldOrderedAuctionLots($auction->Id);
        foreach ($auctionLotGenerator as $auctionLotPosition => $auctionLot) {
            $startClosingDate = clone $auction->StartClosingDate;
            $duration = ($auctionLotPosition + 1) * ($auction->LotStartGapTime + $auction->ExtendTime);
            $startClosingDate = $startClosingDate->add(new DateInterval(sprintf('PT%uS', $duration)));
            $auctionLot->StartClosingDate = $startClosingDate;
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        }
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    private function shouldUpdateForTimedAuction(Auction $auction): bool
    {
        $isAuctionToLotDates = $auction->isTimedScheduled()
            && $auction->isAuctionToItemsDateAssignment();
        return $isAuctionToLotDates;
    }

    /**
     * @param Auction $auction
     * @param int $editorUserId
     */
    private function updateForTimedAuction(Auction $auction, int $editorUserId): void
    {
        if (!$this->shouldUpdateForTimedAuction($auction)) {
            return;
        }

        $isStaggerClosing = $auction->StaggerClosing > 0
            && $auction->LotsPerInterval > 0;

        $startDate = clone $auction->StartDate;
        $startClosingDate = clone $auction->StartClosingDate;
        $auctionLotGenerator = $this->yieldOrderedAuctionLots($auction->Id);
        foreach ($auctionLotGenerator as $auctionLotPosition => $auctionLot) {
            $isBulkMasterLot = $auctionLot->hasMasterRole()
                && $this->getLotBulkGroupExistenceChecker()->existPiecemealGrouping($auctionLot->Id);
            //Previous lots may affect the bulk master lot
            if ($isBulkMasterLot) {
                $auctionLot->Reload();
            }

            // all unsold items end dates that were recalculated must became active again
            if ($auctionLot->isUnsold()) {
                $auctionLot->toActive();
            }

            $isTimedItem = $this->getTimedItemExistenceChecker()->exist($auctionLot->LotItemId, $auctionLot->AuctionId);

            if ($isStaggerClosing) {
                $lotOrderNumber = $auctionLotPosition + 1;
                $startClosingDate = $this->createStaggerClosingHelper()->calcEndDate(
                    $auction->StartClosingDate,
                    $auction->LotsPerInterval,
                    $auction->StaggerClosing,
                    $lotOrderNumber
                );
            }

            if (
                $isTimedItem
                && $auctionLot->StartClosingDate != $startClosingDate
            ) {
                $auctionLot->TextMsgNotified = false;
            }
            $auctionLot->TimezoneId = $auction->TimezoneId;

            //Bulk master lot date updates in observers
            if ($isBulkMasterLot) {
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                continue;
            }

            $timedAuctionLotDates = TimedAuctionLotDates::new()
                ->setStartBiddingDate($startDate)
                ->setStartClosingDate($startClosingDate);
            $this->createAuctionLotDateAssignor()->assignForTimed($auctionLot, $timedAuctionLotDates, $editorUserId);
        }
    }

    /**
     * @param int $auctionId
     * @return Generator|AuctionLotItem[]
     */
    private function yieldOrderedAuctionLots(int $auctionId): Generator
    {
        $auctionLotGenerator = $this->getAuctionLotLoader()->yieldByAuctionId($auctionId);
        return $auctionLotGenerator;
    }
}
