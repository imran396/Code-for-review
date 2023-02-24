<?php
/**
 * SAM-3228: Changes to auction end time editing.
 *
 * This is helper for Auction Edit page controller.
 * Is used to reduce the likelihood of accidentally closing an ongoing auction.
 * We define some auction settings and end date, that user want to save.
 * Class can check, if auction end date was changed manually (old form values are different to current form values).
 * Class can find earlier lot's end date and compare it with current date. So it detects,
 * if there will be lots with end date in the past after auction save. Then we should inform admin,
 * that some active lots will be automatically closed (by /bin/cron/auction_closer.php).
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Apr 15, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 * @property int $auctionId                          Checked auction
 * @property DateTime $startClosingDateOnFormLoad    Initial auction start closing date. Value we had on page load. Used to check if start closing date was changed manually.
 * @property DateTime $startClosingDate              Start closing date (currently on form)
 * @property int $staggerClosing                  Staggered lots Closing interval (currently on form)
 * @property int $dateAssignmentStrategies                Auction->DateAssignmentStrategy value (currently on form)
 */

namespace Sam\Qform\Auction;

use DateTime;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class StartClosingDateCheckerTimed
 * @package Sam\Qform\Auction
 */
class StartClosingDateCheckerTimed extends CustomizableClass
{
    use AuctionAwareTrait;
    use CurrentDateTrait;
    use FormStateLongevityAwareTrait;
    use PositionalAuctionLotLoaderAwareTrait;
    use StaggerClosingHelperCreateTrait;

    protected ?DateTime $startClosingDate = null;
    protected ?DateTime $startClosingDateOnFormLoad = null;
    protected ?int $dateAssignmentStrategy = null;
    protected ?int $lotsPerInterval = null;
    protected ?int $staggerClosing = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check whether date changed or not.
     *
     * @return bool
     */
    public function isStartClosingDateChangedManually(): bool
    {
        $isDateChanged = $this->startClosingDate != $this->startClosingDateOnFormLoad;
        return $isDateChanged;
    }

    /**
     * Check lots are immediately closed or not.
     *
     * @return bool
     */
    public function areLotsToClose(): bool
    {
        $earliestLotEndDate = $this->getEarliestLotEndDate();

        if (!$this->startClosingDateOnFormLoad || !$earliestLotEndDate) {
            return false;
        }

        $areLotsToClose = $earliestLotEndDate < $this->getCurrentDateUtc();
        return $areLotsToClose;
    }

    /**
     * Get earliest lot end date UTC
     *
     * @return DateTime
     */
    private function getEarliestLotEndDate(): ?DateTime
    {
        $endDate = null;
        $auction = $this->getAuction();
        if ($auction) {
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            if ($auction->ExtendAll) {
                $activeLot = $this->getPositionalAuctionLotLoader()
                    ->loadFirstLot($auction->Id, [Constants\Lot::LS_ACTIVE]);
                if ($activeLot) {
                    if ($this->staggerClosing) {
                        $endDate = $this->createStaggerClosingHelper()
                            ->calcEndDate(
                                $this->startClosingDate,
                                $this->lotsPerInterval,
                                $this->staggerClosing,
                                $activeLot->Order
                            );
                    } else {
                        $endDate = $this->startClosingDate;
                    }
                }
            } elseif ($this->staggerClosing) {
                $activeOrUnsoldLot = $this->getPositionalAuctionLotLoader()
                    ->loadFirstLot($auction->Id, [Constants\Lot::LS_ACTIVE, Constants\Lot::LS_UNSOLD]);

                if ($activeOrUnsoldLot) {
                    $endDate = $this->createStaggerClosingHelper()
                        ->calcEndDate(
                            $this->startClosingDate,
                            $this->lotsPerInterval,
                            $this->staggerClosing,
                            $activeOrUnsoldLot->Order
                        );
                }
            } elseif ($auctionStatusPureChecker->isAuctionToItemsDateAssignment($this->dateAssignmentStrategy)) {
                $endDate = $this->startClosingDate;
            }
        }

        return $endDate;
    }

    /**
     * @param DateTime|null $startClosingDate
     * @return static
     */
    public function setStartClosingDateOnFormLoad(?DateTime $startClosingDate): static
    {
        $this->startClosingDateOnFormLoad = $startClosingDate;
        return $this;
    }

    /**
     * @param DateTime|null $startClosingDate
     * @return static
     */
    public function setStartClosingDate(?DateTime $startClosingDate): static
    {
        $this->startClosingDate = $startClosingDate;
        return $this;
    }

    /**
     * @param int|null $lotsPerInterval
     * @return static
     */
    public function setLotsPerInterval(?int $lotsPerInterval): static
    {
        $this->lotsPerInterval = $lotsPerInterval;
        return $this;
    }

    /**
     * @param int|null $staggerClosing
     * @return static
     */
    public function setStaggerClosing(?int $staggerClosing): static
    {
        $this->staggerClosing = $staggerClosing;
        return $this;
    }

    /**
     * @param int $dateAssignmentStrategy
     * @return static
     */
    public function setDateAssignmentStrategy(int $dateAssignmentStrategy): static
    {
        $this->dateAssignmentStrategy = $dateAssignmentStrategy;
        return $this;
    }
}
