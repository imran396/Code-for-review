<?php

namespace Sam\AuctionLot\EndDateExtender;

use Auction;
use AuctionCache;
use AuctionLotItem;
use DateInterval;
use DateTime;
use Exception;
use Sam\Auction\AuctionDynamic\Storage\DataManager as AuctionDynamicDataManager;
use Sam\Auction\Date\AuctionEndDateDetectorCreateTrait;
use Sam\Auction\Date\StartEndPeriod\TimedAuctionDateAssignorAwareTrait;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Load\AuctionDynamicLoaderAwareTrait;
use Sam\Auction\Storage\DataManager as AuctionDataManager;
use Sam\AuctionLot\Cache\Storage\DataManager as AuctionLotCacheDataManager;
use Sam\AuctionLot\Closer\TimedCloser;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\EndDateExtender\Storage\DataManager as EndDateExtenderDataManager;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Bidding\BidTransaction\Place\BidDateAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionDynamic\AuctionDynamicWriteRepositoryAwareTrait;

/**
 * Lot end date extending service
 * Related tickets:
 * SAM-1940: MarknetLive - "Extend All" auction option
 * SAM-1945: MarknetLive - extend from current time
 * SAM-2001: Timed bidding transaction improvements
 * SAM-1928: MarknetLive - Timed online auto extend on auction level
 * SAM-2706: AuctionHQ - extend all with offset
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Mar 27, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @method Auction getAuction(bool $isReadOnlyDb = false) : Auction
 * @method AuctionCache getAuctionCache(bool $isReadOnlyDb = false) : AuctionCache
 * @method AuctionLotItem getAuctionLot(bool $isReadOnlyDb = false) : AuctionLotItem
 * @method DateTime getBidDateUtc() : DateTime
 */
class Service extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionDynamicLoaderAwareTrait;
    use AuctionDynamicWriteRepositoryAwareTrait;
    use AuctionEndDateDetectorCreateTrait;
    use AuctionLotAwareTrait;
    use AuctionLotDateAssignorCreateTrait;
    use AuctionWriteRepositoryAwareTrait;
    use BidDateAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use SettingsManagerAwareTrait;
    use StaggerClosingHelperCreateTrait;
    use TimedAuctionDateAssignorAwareTrait;

    /**
     * @var EndDateExtenderDataManager|null
     */
    protected ?EndDateExtenderDataManager $dataMgr = null;
    /**
     * @var AuctionDataManager|null
     */
    protected ?AuctionDataManager $auctionDataMgr = null;
    /**
     * @var  AuctionDynamicDataManager|null
     */
    protected ?AuctionDynamicDataManager $auctionDynamicDataMgr = null;
    /**
     * @var AuctionLotCacheDataManager|null
     */
    protected ?AuctionLotCacheDataManager $auctionLotCacheDataMgr = null;
    /**
     * @var int|null
     */
    private ?int $interval = null;
    /**
     * @var int|null
     */
    protected ?int $editorUserId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init instance with defaults, inject dependencies
     * @return static
     */
    public function initInstance(): static
    {
        $this->setDataManager(EndDateExtenderDataManager::new());
        $this->setAuctionDataManager(AuctionDataManager::new());
        $this->setAuctionDynamicDataManager(AuctionDynamicDataManager::new());
        $this->setAuctionLotCacheDataManager(AuctionLotCacheDataManager::new());
        return $this;
    }

    /**
     * Setter for main data manager specific for this module
     * @param EndDateExtenderDataManager $dataMgr
     * @return Service
     */
    public function setDataManager(EndDateExtenderDataManager $dataMgr): Service
    {
        $this->dataMgr = $dataMgr;
        return $this;
    }

    /**
     * Setter for auction data manager
     * @param AuctionDataManager $dataMgr
     * @return Service
     */
    public function setAuctionDataManager(AuctionDataManager $dataMgr): Service
    {
        $this->auctionDataMgr = $dataMgr;
        return $this;
    }

    /**
     * Setter for auction dynamic data manager
     * @param AuctionDynamicDataManager $dataMgr
     * @return Service
     */
    public function setAuctionDynamicDataManager(AuctionDynamicDataManager $dataMgr): Service
    {
        $this->auctionDynamicDataMgr = $dataMgr;
        return $this;
    }

    /**
     * Setter for auction lot item cache data manager
     * @param AuctionLotCacheDataManager $dataMgr
     * @return Service
     */
    public function setAuctionLotCacheDataManager(AuctionLotCacheDataManager $dataMgr): Service
    {
        $this->auctionLotCacheDataMgr = $dataMgr;
        return $this;
    }

    /**
     * @param int $editorUserId
     * @return static
     */
    public function setEditorUserId(int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * Lock all entries, which will be used (saved) in the process of lot end date auto-extending
     */
    public function lockInTransactionProcessingEntries(): void
    {
        if ($this->checkMustBeExtended()) {
            $this->auctionDataMgr->lockInTransaction($this->getAuctionId());
            $this->auctionDynamicDataMgr->lockInTransaction($this->getAuctionId());
            // We don't modify auction_cache and don't need to lock it
            // $this->auctionCacheDataMgr->lockInTransaction($this->auction->Id);
            if (!$this->getAuction()->ExtendAll) {
                $this->auctionLotCacheDataMgr->lockInTransaction($this->getAuctionLotId());
            }
        }
    }

    /**
     * Auto extend lot end date
     * @return bool
     */
    public function extend(): bool
    {
        $auction = $this->getAuction();
        // Actualize updated entities before assigning new values and saving. SAM-4179
        // Else they still could have old RowVersion and EndDate, because they were modified in another thread
        $auction->Reload();
        $this->getAuctionLot()->Reload();
        $auctionDynamic = $this->getAuctionDynamicLoader()->load($this->getAuctionId());
        $auctionDynamic?->Reload();

        log_info(function () use ($auction): string {
            $message = 'End date auto extending process IS '
                . ($this->checkMustBeExtended() ? '' : 'NOT ') . 'started for ';
            if ($auction->ExtendAll) {
                $message .= 'all lot items in auction';
                $logData = [
                    'a' => $this->getAuctionId(),
                    'Auction end date (utc)' => $auction->EndDate->format(Constants\Date::ISO) . ' UTC',
                ];
            } else {
                $endDateUtc = $this->getEndDate($this->getAuctionLot());
                $message .= 'in auction';
                $logData = [
                    'li' => $this->getAuctionLot()->LotItemId,
                    'a' => $this->getAuctionId(),
                    'Lot end date (utc)' => $endDateUtc->format(Constants\Date::ISO) . ' UTC',
                ];
            }
            $staggerClosingLogData = ['Staggered closing' => 'Off'];
            if ($auction->StaggerClosing) {
                $staggerClosingLogData = [
                    'Staggered closing (min)' => $auction->StaggerClosing,
                    'Lots per interval' => $auction->LotsPerInterval,
                ];
            }
            $logData = array_merge(
                $logData,
                [
                    'Bid date (utc)' => $this->getBidDateUtc()->format(Constants\Date::ISO) . ' UTC.',
                    'Time difference (min)' => $this->getTimeDiff(),
                    'Extending interval (min)' => $this->getInterval(),
                    'Extend all' => (int)$auction->ExtendAll,
                    'Extend from current time' => (int)$this->isExtendFromCurrentTimeEnabled(),
                ],
                $staggerClosingLogData
            );
            $message .= composeSuffix($logData);
            return $message;
        });

        $success = false;
        if ($this->checkMustBeExtended()) {
            if ($auction->ExtendAll) {
                if ($auction->StaggerClosing) {
                    // we need to close unclosed lots before auction end date extending
                    TimedCloser::new()
                        ->setBidDateUtc($this->getBidDateUtc())
                        ->filterAuctionId($this->getAuctionId())
                        ->close($this->editorUserId);
                }
                $this->extendAuctionEndDate($this->editorUserId);
            } else {
                // Actualize entity before assigning new values and saving
                $this->extendLotEndDate($this->getAuctionLot());
            }

            // To actualize date change outside (because auction object is passed by reference)
            // it is changed in TimedAuctionDateAssignor::updateDateFromLots()
            $auction->Reload();
            $success = true;
        }
        return $success;
    }

    /**
     * Return extending interval
     * @return int
     */
    public function getInterval(): int
    {
        if ($this->interval === null) {
            $auction = $this->getAuction();
            if ($auction->ExtendTime === null) {
                $accountId = $auction->AccountId;
                $this->interval = (int)$this->getSettingsManager()->get(Constants\Setting::EXTEND_TIME_TIMED, $accountId);
            } else {
                $this->interval = (int)$auction->ExtendTime;
            }
        }
        return $this->interval;
    }

    /**
     * Choose extending strategy and extend auction date
     * Is called for Auction->ExtendAll on only
     * @param int $editorUserId
     * @return void
     */
    public function extendAuctionEndDate(int $editorUserId): void
    {
        if ($this->isExtendFromCurrentTimeEnabled()) {
            $this->addIntervalToAuctionCurrentDate($editorUserId);
        } else {
            $this->addIntervalToAuctionStartClosingDate($editorUserId);
        }
        $auction = $this->getAuction();
        $auction->EndDate = $this->createAuctionEndDateDetector()->detect($auction);
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
    }

    /**
     * Check if all conditions are met to start lot end date extending
     * @return bool
     */
    protected function checkMustBeExtended(): bool
    {
        $timeDiff = $this->getTimeDiff();
        $mustBeExtended = $this->getInterval() > 0
            && Floating::gt($timeDiff, 0)
            && Floating::lt($timeDiff, $this->getInterval());
        return $mustBeExtended;
    }

    /**
     * Return time difference between lot end date and bid date
     * @return float
     */
    protected function getTimeDiff(): float
    {
        $endDateUtc = $this->getEndDate($this->getAuctionLot());
        $timeDiff = round(($endDateUtc->getTimestamp() - $this->getBidDateUtcTimestamp()) / 60, 2);
        return $timeDiff;
    }

    /**
     * Return end date in UTC of item.
     * It is dependent on auction start closing date in case of enabled "extend all" option.
     * @param AuctionLotItem|null $auctionLot
     * @return DateTime
     */
    protected function getEndDate(AuctionLotItem $auctionLot = null): DateTime
    {
        $endDateUtc = null;
        $auction = $this->getAuction();
        if ($auction->ExtendAll) {
            if ($auction->StaggerClosing) {
                $endDateUtc = $this->createStaggerClosingHelper()
                    ->calcEndDate(
                        $this->getAuctionDynamicOrCreate()->ExtendAllStartClosingDate,
                        $auction->LotsPerInterval,
                        $auction->StaggerClosing,
                        $this->getAuctionLot()->Order
                    );
            } else {
                $endDateUtc = clone $this->getAuctionDynamicOrCreate()->ExtendAllStartClosingDate;
            }
        } elseif ($auctionLot) {
            $endDateUtc = clone $auctionLot->EndDate;
        }
        if (!$endDateUtc) {
            log_error("Exceptional situation - End date cannot be detected" . composeSuffix(['a' => $this->getAuctionId()]));
            $endDateUtc = $this->getCurrentDateUtc();
        }
        return $endDateUtc;
    }

    /**
     * Choose extending strategy and extend lot date
     * @param AuctionLotItem $auctionLot
     * @return void
     */
    protected function extendLotEndDate(AuctionLotItem $auctionLot): void
    {
        if ($this->isExtendFromCurrentTimeEnabled()) {
            $this->addIntervalToLotCurrentDate($auctionLot);
        } else {
            $this->addIntervalToLotEndDate($auctionLot, $this->editorUserId);
        }
        $this->getAuctionLot()->Reload();

        $auction = $this->getAuction();
        $auctionDateAssignor = $this->getTimedAuctionDateAssignor()->setAuction($auction);
        if ($auctionDateAssignor->isAllowed()) {
            $auctionDateAssignor->updateEndDateFromLots($this->editorUserId);
        }
    }

    /**
     * Add minutes interval to timed lot end date
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return bool
     * @throws Exception
     */
    public function addIntervalToLotEndDate(AuctionLotItem $auctionLot, int $editorUserId): bool
    {
        $oldEndDateIso = $auctionLot->EndDate->format(Constants\Date::ISO);
        $endDate = clone $auctionLot->EndDate;                                 // we assign end date this way,
        $endDate->add(new DateInterval('PT' . $this->getInterval() . 'M')); // because we need that observer will catch
        $auctionLotDates = TimedAuctionLotDates::new()->setEndDate($endDate);
        $this->createAuctionLotDateAssignor()->assignForTimed($this->getAuctionLot(), $auctionLotDates, $editorUserId);
        log_info(function () use ($auctionLot, $oldEndDateIso): string {
            $logData = [
                'li' => $auctionLot->LotItemId,
                'a' => $auctionLot->AuctionId,
                'New end date' => $auctionLot->EndDate->format(Constants\Date::ISO) . ' UTC',
                'Old end date' => $oldEndDateIso . ' UTC',
            ];
            return "Interval ({$this->getInterval()} min) added to end time of lot item in auction" . composeSuffix($logData);
        });
        return true;
    }

    /**
     * Add minutes interval to timed lot current date (SAM-1945)
     * @param AuctionLotItem $auctionLot
     * @return bool true - if end time was extended successfully
     */
    protected function addIntervalToLotCurrentDate(AuctionLotItem $auctionLot): bool
    {
        $lotEndDateOld = $auctionLot->EndDate;
        $lotEndDateUtc = $this->getEndDate($auctionLot);
        // Add interval to current time
        $newEndDateUtc = $this->applyIntervalOnDate($this->getBidDateUtc());
        // New end date should be later, than lot end date
        if ($newEndDateUtc->getTimestamp() < $lotEndDateUtc->getTimestamp()) {
            return false;
        }

        $timeDiff = $newEndDateUtc->getTimestamp() - $lotEndDateUtc->getTimestamp();
        $endDate = clone $auctionLot->EndDate;    // we need to clone, so modification of property will be detected in observer
        $endDate->setTimestamp($endDate->getTimestamp() + $timeDiff);

        $auctionLotDates = TimedAuctionLotDates::new()->setEndDate($endDate);
        $this->createAuctionLotDateAssignor()->assignForTimed($auctionLot, $auctionLotDates, $this->editorUserId);

        log_info(function () use ($auctionLot, $lotEndDateOld): string {
            $logData = [
                'li' => $auctionLot->LotItemId,
                'a' => $auctionLot->AuctionId,
                'New end date' => $auctionLot->EndDate->format(Constants\Date::ISO) . ' UTC',
                'Old end date' => $lotEndDateOld->format(Constants\Date::ISO) . ' UTC',
            ];
            return "Interval ({$this->getInterval()} min) added to current time of lot item in auction" . composeSuffix($logData);
        });
        return true;
    }

    /**
     * @return bool
     */
    protected function isExtendFromCurrentTimeEnabled(): bool
    {
        $isEnabled = $this->cfg()->get('core->auction->extendFromCurrentTime->enabled')
            && $this->getAuction()->ExtendFromCurrentTime;
        return $isEnabled;
    }

    /**
     * Add minutes interval to auction start closing date
     * @param int $editorUserId
     * @return bool
     */
    protected function addIntervalToAuctionStartClosingDate(int $editorUserId): bool
    {
        $auctionDynamic = $this->getAuctionDynamicOrCreate();
        $oldStartClosingDateIso = $auctionDynamic->ExtendAllStartClosingDate->format(Constants\Date::ISO);
        // we assign end date this way, because we need that observer will catch modification of EndDate property
        $auctionStartClosingDate = clone $auctionDynamic->ExtendAllStartClosingDate;
        $auctionDynamic->ExtendAllStartClosingDate = $this->applyIntervalOnDate($auctionStartClosingDate);
        $this->getAuctionDynamicWriteRepository()->saveWithModifier($auctionDynamic, $editorUserId);
        log_info(function () use ($auctionDynamic, $oldStartClosingDateIso): string {
            $logData = [
                'a' => $this->getAuctionId(),
                'New start closing date' => $auctionDynamic->ExtendAllStartClosingDate->format(Constants\Date::ISO) . ' UTC',
                'Old start closing date' => $oldStartClosingDateIso . ' UTC',
            ];
            return "Interval ({$this->getInterval()} min) added to end time of auction" . composeSuffix($logData);
        });
        return true;
    }

    /**
     * Add minutes interval to auction current date
     * Is called for Auction->ExtendAll on only
     * @param int $editorUserId
     * @return bool true - if end time was extended successfully
     */
    protected function addIntervalToAuctionCurrentDate(int $editorUserId): bool
    {
        $auctionDynamic = $this->getAuctionDynamicOrCreate();
        $auctionStartClosingDateOld = $auctionDynamic->ExtendAllStartClosingDate;
        $lotEndDateUtc = $this->getEndDate();
        // Add interval to current time
        $newEndDateUtc = $this->applyIntervalOnDate($this->getBidDateUtc());
        // New end date should be later, than lot end date
        if ($newEndDateUtc->getTimestamp() < $lotEndDateUtc->getTimestamp()) {
            return false;
        }

        $timeDiff = $newEndDateUtc->getTimestamp() - $lotEndDateUtc->getTimestamp();
        // we should clone, so modification of property will be detected in observer
        $auctionStartClosingDate = clone $auctionDynamic->ExtendAllStartClosingDate;
        $auctionStartClosingDate->setTimestamp($auctionStartClosingDate->getTimestamp() + $timeDiff);

        $auctionDynamic->ExtendAllStartClosingDate = $auctionStartClosingDate;
        $this->getAuctionDynamicWriteRepository()->saveWithModifier($auctionDynamic, $editorUserId);

        log_info(function () use ($auctionDynamic, $auctionStartClosingDateOld): string {
            $logData = [
                'a' => $this->getAuctionId(),
                "New start closing date" => $auctionDynamic->ExtendAllStartClosingDate->format(Constants\Date::ISO) . ' UTC',
                'Old start closing date' => $auctionStartClosingDateOld->format(Constants\Date::ISO) . ' UTC',
            ];
            return "Interval ({$this->getInterval()} min) added to current time of auction" . composeSuffix($logData);
        });
        return true;
    }

    /**
     * @param DateTime $date
     * @return DateTime
     */
    protected function applyIntervalOnDate(DateTime $date): DateTime
    {
        $intervalSpec = 'PT' . $this->getInterval() . 'M';
        try {
            $dateInterval = new DateInterval($intervalSpec);
            $date->add($dateInterval);
        } catch (Exception) {
            log_error(composeLogData(["Interval spec cannot be parsed" => $intervalSpec]));
        }
        return $date;
    }
}
