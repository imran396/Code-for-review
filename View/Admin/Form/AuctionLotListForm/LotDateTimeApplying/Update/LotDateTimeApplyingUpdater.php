<?php
/**
 * SAM-10180: Extract logic of date and time assignment to auction lots collection from the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Update;

use AuctionLotItem;
use DateTimeZone;
use Sam\Auction\Date\StartEndPeriod\TimedAuctionDateAssignorAwareTrait;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Dto\LotDateTimeDto;
use Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Internal\InputDateTimeFactoryCreateTrait;
use Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Update\Internal\Load\DataProviderCreateTrait;

/**
 * Class LotDateTimeApplyingUpdater
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Update
 */
class LotDateTimeApplyingUpdater extends CustomizableClass
{
    use AuctionLotDateAssignorCreateTrait;
    use DataProviderCreateTrait;
    use InputDateTimeFactoryCreateTrait;
    use StaggerClosingHelperCreateTrait;
    use TimedAuctionDateAssignorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(LotDateTimeDto $dateTimeDto, array $lotItemIds, int $auctionId, int $editorUserId): void
    {
        $dateTimeZoneUtc = new DateTimeZone('UTC');
        $startDateTimeUtc = $this->createInputDateTimeFactory()
            ->create(
                $dateTimeDto->startDate,
                $dateTimeDto->startHour,
                $dateTimeDto->startMinute,
                $dateTimeDto->startMeridiem,
                $dateTimeDto->timezoneLocation,
                $dateTimeDto->adminDateFormat
            )
            ->setTimezone($dateTimeZoneUtc);
        $startClosingDateTimeUtc = $this->createInputDateTimeFactory()
            ->create(
                $dateTimeDto->startClosingDate,
                $dateTimeDto->startClosingHour,
                $dateTimeDto->startClosingMinute,
                $dateTimeDto->startClosingMeridiem,
                $dateTimeDto->timezoneLocation,
                $dateTimeDto->adminDateFormat
            )
            ->setTimezone($dateTimeZoneUtc);
        $timezoneId = $this->createDataProvider()->detectTimezoneIdByLocationOrCreatePersisted($dateTimeDto->timezoneLocation);

        $auctionLots = $this->createDataProvider()->loadOrderedAuctionLots($lotItemIds, $auctionId);
        foreach ($auctionLots as $index => $auctionLot) {
            $dates = TimedAuctionLotDates::new()
                ->setStartBiddingDate($startDateTimeUtc)
                ->setStartClosingDate($startClosingDateTimeUtc);
            if (
                $dateTimeDto->staggerClosingInterval > 0
                && $dateTimeDto->staggerClosingLotsPerInterval > 0
            ) {
                $dates->setStartClosingDate(
                    $this->createStaggerClosingHelper()->calcEndDate(
                        $startClosingDateTimeUtc,
                        $dateTimeDto->staggerClosingLotsPerInterval,
                        $dateTimeDto->staggerClosingInterval,
                        $index + 1
                    )
                );
            }
            $this->updateAuctionLot($auctionLot, $dates, $timezoneId, $editorUserId);
        }

        $auctionDateAssignor = $this->getTimedAuctionDateAssignor()->setAuctionId($auctionId);
        if ($auctionDateAssignor->shouldUpdate()) {
            $auctionDateAssignor->updateDateFromLots($editorUserId);
        }
    }

    protected function updateAuctionLot(
        AuctionLotItem $auctionLot,
        TimedAuctionLotDates $dates,
        int $timezoneId,
        int $editorUserId
    ): void {
        if (
            !$auctionLot->StartClosingDate
            || $auctionLot->StartClosingDate != $dates->getStartClosingDate()
        ) {
            $auctionLot->TextMsgNotified = false;
        }
        $auctionLot->TimezoneId = $timezoneId;
        $this->createAuctionLotDateAssignor()->assignForTimed($auctionLot, $dates, $editorUserId);
        log_debug(
            "Updated Lot" . composeSuffix(
                [
                    'lotItemId' => $auctionLot->LotItemId,
                    'auctionId' => $auctionLot->AuctionId,
                    'start' => $dates->getStartBiddingDate()->format(DATE_ATOM),
                    'startClosing' => $dates->getStartClosingDate()->format(DATE_ATOM),
                ]
            )
        );
    }
}
