<?php
/**
 * SAM-10450: Decouple auction date validation logic into internal services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\Date;

use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\AuctionMakerValidator;
use Sam\EntityMaker\Auction\Validate\Constants\ResultCode;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common\CommonDateValidationInput;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common\CommonDateValidationResult;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common\CommonDateValidatorCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Hybrid\HybridDateValidationInput;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Hybrid\HybridDateValidationResult;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Hybrid\HybridDateValidatorCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Live\LiveDateValidationInput;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Live\LiveDateValidationResult;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Live\LiveDateValidatorCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed\TimedDateValidationInput;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed\TimedDateValidationResult;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed\TimedDateValidatorCreateTrait;

/**
 * Class DateValidationIntegrator
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date
 */
class DateValidationIntegrator extends CustomizableClass
{
    use CommonDateValidatorCreateTrait;
    use HybridDateValidatorCreateTrait;
    use LiveDateValidatorCreateTrait;
    use TimedDateValidatorCreateTrait;

    protected const COMMON_DATE_ERROR_CODE_MAP = [
        CommonDateValidationResult::ERR_PUBLISH_DATE_INVALID => ResultCode::PUBLISH_DATE_INVALID,
        CommonDateValidationResult::ERR_UNPUBLISH_DATE_INVALID => ResultCode::UNPUBLISH_DATE_INVALID,
        CommonDateValidationResult::ERR_START_REGISTER_DATE_INVALID => ResultCode::START_REGISTER_DATE_INVALID,
        CommonDateValidationResult::ERR_END_REGISTER_DATE_INVALID => ResultCode::END_REGISTER_DATE_INVALID,
        CommonDateValidationResult::ERR_END_REGISTER_DATE_EARLIER_START_REGISTER_DATE => ResultCode::END_REGISTER_DATE_EARLIER_START_REGISTER_DATE,
        CommonDateValidationResult::ERR_START_BIDDING_DATE_INVALID => ResultCode::START_BIDDING_DATE_INVALID,
    ];

    protected const TIMED_DATE_ERROR_CODE_MAP = [
        TimedDateValidationResult::ERR_START_BIDDING_DATE_REQUIRED => ResultCode::START_BIDDING_DATE_REQUIRED,
        TimedDateValidationResult::ERR_START_CLOSING_DATE_REQUIRED => ResultCode::START_CLOSING_DATE_REQUIRED,
        TimedDateValidationResult::ERR_START_CLOSING_DATE_INVALID => ResultCode::START_CLOSING_DATE_INVALID,
        TimedDateValidationResult::ERR_START_CLOSING_DATE_DO_NOT_MATCH_ITEMS_DATE => ResultCode::START_CLOSING_DATE_DO_NOT_MATCH_ITEMS_DATE,
        TimedDateValidationResult::ERR_START_BIDDING_DATE_DO_NOT_MATCH_ITEMS_DATE => ResultCode::START_BIDDING_DATE_DO_NOT_MATCH_ITEMS_DATE,
    ];

    protected const LIVE_DATE_ERROR_CODE_MAP = [
        LiveDateValidationResult::ERR_START_CLOSING_DATE_REQUIRED => ResultCode::START_CLOSING_DATE_REQUIRED,
        LiveDateValidationResult::ERR_START_CLOSING_DATE_INVALID => ResultCode::START_CLOSING_DATE_INVALID,
        LiveDateValidationResult::ERR_BIDDING_CONSOLE_ACCESS_DATE_REQUIRED => ResultCode::BIDDING_CONSOLE_ACCESS_DATE_REQUIRED,
        LiveDateValidationResult::ERR_BIDDING_CONSOLE_ACCESS_DATE_INVALID => ResultCode::BIDDING_CONSOLE_ACCESS_DATE_INVALID,
        LiveDateValidationResult::ERR_LIVE_END_DATE_REQUIRED => ResultCode::LIVE_END_DATE_REQUIRED,
        LiveDateValidationResult::ERR_LIVE_END_DATE_INVALID => ResultCode::LIVE_END_DATE_INVALID,
        LiveDateValidationResult::ERR_LIVE_END_DATE_EARLIER_START_CLOSING_DATE => ResultCode::LIVE_END_DATE_EARLIER_START_CLOSING_DATE,
        LiveDateValidationResult::ERR_END_PREBIDDING_DATE_INVALID => ResultCode::END_PREBIDDING_DATE_INVALID,
    ];

    protected const HYBRID_DATE_ERROR_CODE_MAP = [
        HybridDateValidationResult::ERR_START_CLOSING_DATE_REQUIRED => ResultCode::START_CLOSING_DATE_REQUIRED,
        HybridDateValidationResult::ERR_START_CLOSING_DATE_INVALID => ResultCode::START_CLOSING_DATE_INVALID,
        HybridDateValidationResult::ERR_BIDDING_CONSOLE_ACCESS_DATE_REQUIRED => ResultCode::BIDDING_CONSOLE_ACCESS_DATE_REQUIRED,
        HybridDateValidationResult::ERR_BIDDING_CONSOLE_ACCESS_DATE_INVALID => ResultCode::BIDDING_CONSOLE_ACCESS_DATE_INVALID,
        HybridDateValidationResult::ERR_BIDDING_CONSOLE_ACCESS_DATE_LATER_START_CLOSING_DATE => ResultCode::BIDDING_CONSOLE_ACCESS_DATE_EARLIER_START_DATE,
        HybridDateValidationResult::ERR_END_PREBIDDING_DATE_INVALID => ResultCode::END_PREBIDDING_DATE_INVALID,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionMakerValidator $auctionMakerValidator
     * @return void
     */
    public function validate(AuctionMakerValidator $auctionMakerValidator): void
    {
        $this->validateCommonDates($auctionMakerValidator);
        $auctionType = $auctionMakerValidator->getInputDto()->auctionType;
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimed($auctionType)) {
            $this->validateTimedAuctionDates($auctionMakerValidator);
        } elseif ($auctionStatusPureChecker->isLive($auctionType)) {
            $this->validateLiveAuctionDates($auctionMakerValidator);
        } elseif ($auctionStatusPureChecker->isHybrid($auctionType)) {
            $this->validateHybridAuctionDates($auctionMakerValidator);
        }
    }

    protected function validateCommonDates(AuctionMakerValidator $auctionMakerValidator): void
    {
        $inputDto = $auctionMakerValidator->getInputDto();
        $configDto = $auctionMakerValidator->getConfigDto();
        $validator = $this->createCommonDateValidator();

        $validationInput = CommonDateValidationInput::new()->fromMakerDto(
            $inputDto,
            $configDto
        );
        $result = $validator->validate($validationInput);

        if ($result->hasSuccess()) {
            return;
        }

        foreach (self::COMMON_DATE_ERROR_CODE_MAP as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $auctionMakerValidator->addError($callerCode);
            }
        }

        log_debug("Common date validation failed" . composeSuffix($result->logData() + $validationInput->logData()));
    }

    protected function validateTimedAuctionDates(AuctionMakerValidator $auctionMakerValidator): void
    {
        $inputDto = $auctionMakerValidator->getInputDto();
        $configDto = $auctionMakerValidator->getConfigDto();
        $validator = $this->createTimedDateValidator();

        $validationInput = TimedDateValidationInput::new()->fromMakerDto(
            $inputDto,
            $configDto
        );
        $result = $validator->validate($validationInput);

        if ($result->hasSuccess()) {
            return;
        }

        foreach (self::TIMED_DATE_ERROR_CODE_MAP as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $auctionMakerValidator->addError($callerCode);
            }
        }

        log_debug("Timed auction date validation failed" . composeSuffix($result->logData() + $validationInput->logData()));
    }

    protected function validateLiveAuctionDates(AuctionMakerValidator $auctionMakerValidator): void
    {
        $inputDto = $auctionMakerValidator->getInputDto();
        $configDto = $auctionMakerValidator->getConfigDto();
        $validator = $this->createLiveDateValidator();

        $validationInput = LiveDateValidationInput::new()->fromMakerDto(
            $inputDto,
            $configDto
        );
        $result = $validator->validate($validationInput);

        if ($result->hasSuccess()) {
            return;
        }

        foreach (self::LIVE_DATE_ERROR_CODE_MAP as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $auctionMakerValidator->addError($callerCode);
            }
        }

        log_debug("Live auction date validation failed" . composeSuffix($result->logData() + $validationInput->logData()));
    }

    protected function validateHybridAuctionDates(AuctionMakerValidator $auctionMakerValidator): void
    {
        $inputDto = $auctionMakerValidator->getInputDto();
        $configDto = $auctionMakerValidator->getConfigDto();
        $validator = $this->createHybridDateValidator();

        $validationInput = HybridDateValidationInput::new()->fromMakerDto(
            $inputDto,
            $configDto
        );
        $result = $validator->validate($validationInput);

        if ($result->hasSuccess()) {
            return;
        }

        foreach (self::HYBRID_DATE_ERROR_CODE_MAP as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $auctionMakerValidator->addError($callerCode);
            }
        }

        log_debug("Hybrid auction date validation failed" . composeSuffix($result->logData() + $validationInput->logData()));
    }
}
