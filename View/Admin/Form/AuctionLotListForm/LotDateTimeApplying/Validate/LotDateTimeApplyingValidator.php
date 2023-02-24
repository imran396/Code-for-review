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

namespace Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Validate;

use Auction;
use DateTimeZone;
use Exception;
use Sam\Core\Constants;
use Sam\Core\Date\Validate\DateFormatValidator;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Dto\LotDateTimeDto;
use Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Internal\InputDateTimeFactoryCreateTrait;
use Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Validate\LotDateTimeApplyingValidationResult as Result;

/**
 * Class LotDateTimeApplyingValidator
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Validate
 */
class LotDateTimeApplyingValidator extends CustomizableClass
{
    use InputDateTimeFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(LotDateTimeDto $dateTimeDto, Auction $auction): LotDateTimeApplyingValidationResult
    {
        $result = Result::new()->construct();
        if (!$this->isApplicableAuction($auction)) {
            return $result->addError(Result::ERR_NOT_APPLICABLE_FOR_AUCTION);
        }

        if (!$this->isValidDateAssignmentStrategy($auction)) {
            return $result->addError(Result::ERR_DATE_ASSIGNMENT_STRATEGY_INVALID);
        }

        try {
            new DateTimeZone($dateTimeDto->timezoneLocation);
        } catch (Exception) {
            $result->addError(Result::ERR_TIMEZONE_INVALID);
        }

        if ($this->isEmptyDate($dateTimeDto->startDate)) {
            $result->addError(Result::ERR_START_DATE_REQUIRED);
        } elseif (!$this->isValidDateFormat($dateTimeDto->startDate, $dateTimeDto->adminDateFormat)) {
            $result->addError(Result::ERR_START_DATE_INVALID_FORMAT);
        }

        if ($this->isEmptyDate($dateTimeDto->startClosingDate)) {
            $result->addError(Result::ERR_START_CLOSING_DATE_REQUIRED);
        } elseif (!$this->isValidDateFormat($dateTimeDto->startClosingDate, $dateTimeDto->adminDateFormat)) {
            $result->addError(Result::ERR_START_CLOSING_DATE_INVALID_FORMAT);
        }

        if (
            !$result->hasError()
            && !$this->isStartDateLessThanStartClosingDate($dateTimeDto)
        ) {
            $result->addError(Result::ERR_START_DATE_GREATER_THAN_START_CLOSING_DATE);
        }
        return $result;
    }

    protected function isStartDateLessThanStartClosingDate(LotDateTimeDto $dateTimeDto): bool
    {
        $startDateTime = $this->createInputDateTimeFactory()->create(
            $dateTimeDto->startDate,
            $dateTimeDto->startHour,
            $dateTimeDto->startMinute,
            $dateTimeDto->startMeridiem,
            $dateTimeDto->timezoneLocation,
            $dateTimeDto->adminDateFormat
        );

        $startClosingDateTime = $this->createInputDateTimeFactory()->create(
            $dateTimeDto->startClosingDate,
            $dateTimeDto->startClosingHour,
            $dateTimeDto->startClosingMinute,
            $dateTimeDto->startClosingMeridiem,
            $dateTimeDto->timezoneLocation,
            $dateTimeDto->adminDateFormat
        );

        return $startDateTime <= $startClosingDateTime;
    }

    protected function isValidDateAssignmentStrategy(Auction $auction): bool
    {
        return !$auction->isTimedScheduled()
            || $auction->isItemsToAuctionDateAssignment();
    }

    protected function isEmptyDate(string $date): bool
    {
        return $date === '';
    }

    protected function isValidDateFormat(string $date, int $adminDateFormat): bool
    {
        $isValid = ($adminDateFormat === Constants\Date::ADF_AU
                && DateFormatValidator::new()->isEnFormatDate($date))
            || ($adminDateFormat === Constants\Date::ADF_US
                && DateFormatValidator::new()->isUsFormatDate($date));
        return $isValid;
    }

    protected function isApplicableAuction(Auction $auction): bool
    {
        return $auction->isTimed() && !$auction->ExtendAll;
    }
}
