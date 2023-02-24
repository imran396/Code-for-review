<?php
/**
 * SAM-8892: Auction Lot entity maker - extract lot# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Validate\AuctionLotMakerValidator;
use Sam\EntityMaker\AuctionLot\Validate\Constants\ResultCode;
use Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate\LotNoValidationInput;
use Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate\LotNoValidationResult;
use Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate\LotNoValidatorCreateTrait;

/**
 * Class LotNoValidationIntegrator
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo
 */
class LotNoValidationIntegrator extends CustomizableClass
{
    use LotNoValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionLotMakerValidator $auctionLotMakerValidator
     * @return void
     */
    public function validate(AuctionLotMakerValidator $auctionLotMakerValidator): void
    {
        $inputDto = $auctionLotMakerValidator->getInputDto();
        $configDto = $auctionLotMakerValidator->getConfigDto();
        $validator = $this->createLotNoValidator()->construct();

        $validationInput = LotNoValidationInput::new()->fromMakerDto(
            $inputDto,
            $configDto
        );
        $result = $validator->validate($validationInput);

        if ($result->hasSuccess()) {
            return;
        }

        $errorCodeMap = [
            LotNoValidationResult::ERR_LOT_NUM_EXIST => ResultCode::LOT_NUM_EXIST,
            LotNoValidationResult::ERR_LOT_NUM_EXT_INVALID => ResultCode::LOT_NUM_EXT_INVALID,
            LotNoValidationResult::ERR_LOT_NUM_INVALID => ResultCode::LOT_NUM_INVALID,
            LotNoValidationResult::ERR_LOT_NUM_PREFIX_INVALID => ResultCode::LOT_NUM_PREFIX_INVALID,
            LotNoValidationResult::ERR_LOT_NUM_REQUIRED => ResultCode::LOT_NUM_REQUIRED,
            LotNoValidationResult::ERR_CONCATENATED_LOT_NO_PARSE_FAILED => ResultCode::LOT_FULL_NUM_PARSE_ERROR,
            LotNoValidationResult::ERR_LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE => ResultCode::LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE,
        ];
        foreach ($errorCodeMap as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $auctionLotMakerValidator->addError($callerCode);
            }
        }

        if ($result->hasErrorByCode(LotNoValidationResult::ERR_LOT_NUM_EXT_INVALID_LENGTH)) {
            $auctionLotMakerValidator->addError(
                ResultCode::LOT_NUM_EXT_INVALID_LENGTH,
                sprintf($auctionLotMakerValidator->errorMessages[ResultCode::LOT_NUM_EXT_INVALID_LENGTH], $result->lotNoMaxLength)
            );
        }

        if ($result->hasErrorByCode(LotNoValidationResult::ERR_CONCATENATED_LOT_NO_PARSE_FAILED)) {
            $auctionLotMakerValidator->addError(ResultCode::LOT_FULL_NUM_PARSE_ERROR, $result->errorMessage());
        }

        log_debug("Lot# validation failed" . composeSuffix($result->logData() + $validationInput->logData()));
    }

}
