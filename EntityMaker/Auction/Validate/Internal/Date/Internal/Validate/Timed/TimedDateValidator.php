<?php
/**
 * SAM-10450: Decouple auction date validation logic into internal services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed;

use DateTime;
use DateTimeZone;
use Exception;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\AuctionDateValidationHelper;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed\Internal\Load\DataProviderCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed\TimedDateValidationInput as Input;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed\TimedDateValidationResult as Result;

/**
 * Class TimedDateValidator
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed
 */
class TimedDateValidator extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        $eventType = $this->detectEventType($input);
        if ($eventType === Constants\Auction::ET_SCHEDULED) {
            $validationHelper = AuctionDateValidationHelper::new();
            if (!$input->auctionId) {
                $result = $validationHelper->checkRequired($input->startBiddingDate, Result::ERR_START_BIDDING_DATE_REQUIRED, $result);
                $result = $validationHelper->checkRequired($input->startClosingDate, Result::ERR_START_CLOSING_DATE_REQUIRED, $result);
            }
            $result = $validationHelper->checkNotEmpty($input->startBiddingDate, Result::ERR_START_BIDDING_DATE_REQUIRED, $result);
            $result = $validationHelper->checkNotEmpty($input->startClosingDate, Result::ERR_START_CLOSING_DATE_REQUIRED, $result);
            $result = $validationHelper->checkDate($input->startClosingDate, Result::ERR_START_CLOSING_DATE_INVALID, $input->mode, $result);
            $result = $this->checkAuctionAndItemDates($input, $result);
        }
        return $result;
    }

    /**
     * Check that auction and its items dates are correct
     * 1. when items dates applied to auction - check that auction date was not changed by user.
     * 2. when auction dates should be applied to items - just skip this check as item dates should be updated to
     *    auction date with AuctionLotListForm::setLotDateFromAuctionUpload()
     */
    protected function checkAuctionAndItemDates(Input $input, Result $result): Result
    {
        $dataProvider = $this->createDataProvider();
        if (
            (
                !$input->startClosingDate
                && !$input->startBiddingDate
            )
            || !$input->auctionId
            || !$this->isItemsToAuctionDateAssignment($input)
            || $dataProvider->loadTotalLots($input->auctionId) === 0
        ) {
            return $result;
        }

        $validationHelper = AuctionDateValidationHelper::new();
        try {
            if (
                $input->startClosingDate
                && $validationHelper->isValidDateFormat($input->startClosingDate, $input->mode)
            ) {
                $startClosingDate = new DateTime($input->startClosingDate, new DateTimeZone($input->timezone ?: 'UTC'));
                $actualStartClosingDate = $dataProvider->loadStartClosingDate($input->auctionId);
                if ($actualStartClosingDate != $startClosingDate) {
                    $result->addError(Result::ERR_START_CLOSING_DATE_DO_NOT_MATCH_ITEMS_DATE);
                }
            }
            if (
                $input->startBiddingDate
                && $validationHelper->isValidDateFormat($input->startBiddingDate, $input->mode)
            ) {
                $startBiddingDate = new DateTime($input->startBiddingDate, new DateTimeZone($input->timezone ?: 'UTC'));
                $actualStartBiddingDate = $dataProvider->loadStartBiddingDate($input->auctionId);
                if ($actualStartBiddingDate != $startBiddingDate) {
                    $result->addError(Result::ERR_START_BIDDING_DATE_DO_NOT_MATCH_ITEMS_DATE);
                }
            }
        } catch (Exception) {
            log_warning('Invalid timezone. Auction And Items Dates checking aborted.');
        }
        return $result;
    }

    protected function isItemsToAuctionDateAssignment(Input $input): bool
    {
        $dateAssignmentStrategy = $input->dateAssignmentStrategy
            ?? $this->createDataProvider()->loadDateAssignmentStrategy($input->auctionId);
        return AuctionStatusPureChecker::new()->isItemsToAuctionDateAssignment($dateAssignmentStrategy);
    }

    protected function detectEventType(Input $input): ?int
    {
        if ($input->eventType !== null) {
            $eventType = (int)array_search($input->eventType, Constants\Auction::$eventTypeNames, true);
        } elseif ($input->eventTypeId !== null) {
            $eventType = (int)$input->eventTypeId;
        } else {
            $eventType = $this->createDataProvider()->loadEventType($input->auctionId) ?? Constants\Auction::ET_SCHEDULED;
        }
        return $eventType;
    }
}
