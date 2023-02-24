<?php
/**
 * SAM-4914: Lot-to-Auction and Auction-to-Lot Start/End date assigning modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Date\StartEndPeriod;

use Auction;
use Sam\Auction\Date\Calculate\AuctionDateFromLotsDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class TimedAuctionDateAssignor
 * @package Sam\Auction\Date\StartEndPeriod
 */
class TimedAuctionDateAssignor extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionDateFromLotsDetectorCreateTrait;
    use AuctionWriteRepositoryAwareTrait;
    use TimezoneLoaderAwareTrait;

    protected string $oldStartDateIso = '';
    protected string $oldEndDateIso = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function shouldUpdate(): bool
    {
        $should = $this->isAllowed()
            && $this->isDateAssigmentStrategyItemsToAuction();
        return $should;
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        $auction = $this->getAuction();
        return $auction
            && $auction->isTimedScheduled()
            && !$auction->ExtendAll;
    }

    /**
     * @return bool
     */
    private function isDateAssigmentStrategyItemsToAuction(): bool
    {
        $auction = $this->getAuction();
        return $auction
            && $auction->isItemsToAuctionDateAssignment();
    }

    /**
     * Update auction dates based on the auction's lots min start date and max end date
     * @param int $editorUserId
     */
    public function updateDateFromLots(int $editorUserId): void
    {
        if (!$this->isAllowed()) {
            log_error(
                'Auction dates assigning according to lot dates is rejected for current auction state'
                . composeSuffix(['a' => $this->getAuctionId()])
            );
            return;
        }
        $auction = $this->prepareAuction();
        $this->updateAuctionDates($auction, $editorUserId);
        $this->logDateChange($auction);
    }

    /**
     * Calculate dates and assign
     * @param Auction $auction
     * @param int $editorUserId
     */
    protected function updateAuctionDates(Auction $auction, int $editorUserId): void
    {
        $auctionDateFromLotsDetector = $this->createAuctionDateFromLotsDetector();
        $startBiddingDate = $auctionDateFromLotsDetector->detectStartBiddingDate($auction->Id);
        if ($startBiddingDate !== null) {
            $auction->StartBiddingDate = $startBiddingDate;
            $auction->StartDate = clone $startBiddingDate;
        }
        $startClosingDate = $auctionDateFromLotsDetector->detectStartClosingDate($auction->Id);
        if ($startClosingDate !== null) {
            $auction->StartClosingDate = $startClosingDate;
            $auction->EndDate = clone $startClosingDate;
        }
        $endDate = $auctionDateFromLotsDetector->detectEndDate($auction->Id);
        if ($endDate !== null) {
            $auction->EndDate = $endDate;
        }
        $auction->toItemsToAuctionDateAssignment();
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
    }

    protected function prepareAuction(): Auction
    {
        /** @var Auction $auction */
        $auction = $this->getAuction();
        // we want to operate with fresh object for reducing probability of race condition, SAM-4179
        $auction->Reload();
        // Store for log
        $this->oldStartDateIso = $auction->StartDate ? $auction->StartDate->format(Constants\Date::ISO) : '';
        $this->oldEndDateIso = $auction->EndDate ? $auction->EndDate->format(Constants\Date::ISO) : '';
        return $auction;
    }

    public function updateEndDateFromLots(int $editorUserId): void
    {
        if (!$this->isAllowed()) {
            log_error(
                'Auction dates assigning according to lot dates is rejected for current auction state'
                . composeSuffix(['a' => $this->getAuctionId()])
            );
            return;
        }

        $auction = $this->prepareAuction();
        $auctionDateFromLotsDetector = $this->createAuctionDateFromLotsDetector();
        $endDate = $auctionDateFromLotsDetector->detectEndDate($auction->Id);
        if ($endDate !== null) {
            $auction->EndDate = $endDate;
        }
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
        $this->logDateChange($auction);
    }

    protected function logDateChange(Auction $auction): void
    {
        $startDateIso = $auction->StartDate ? $auction->StartDate->format(Constants\Date::ISO) : '';
        $endDateIso = $auction->EndDate ? $auction->EndDate->format(Constants\Date::ISO) : '';
        $startDateLog = $startDateIso === $this->oldStartDateIso
            ? "start: not changed {$startDateIso}"
            : "start: {$this->oldStartDateIso} => {$startDateIso}";
        $endDateLog = $endDateIso === $this->oldEndDateIso
            ? "end: not changed {$endDateIso}"
            : "end: {$this->oldEndDateIso} => {$endDateIso}";
        log_info(
            "Auction start/end dates assigned according lot dates"
            . composeSuffix(['a' => $auction->Id]) . ", {$startDateLog}, {$endDateLog}"
        );
    }
}
