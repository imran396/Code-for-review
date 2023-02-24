<?php
/**
 * SAM-8891: Auction entity-maker - Extract sale# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\SaleNo;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\AuctionMakerValidator;
use Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate\SaleNoValidationInput;
use Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate\SaleNoValidationResult;
use Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate\SaleNoValidatorCreateTrait;
use Sam\EntityMaker\Auction\Validate\Constants\ResultCode;


class SaleNoValidationIntegrator extends CustomizableClass
{
    use SaleNoValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
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
        $inputDto = $auctionMakerValidator->getInputDto();
        $configDto = $auctionMakerValidator->getConfigDto();
        $validator = $this->createSaleNoValidator();

        $validationInput = SaleNoValidationInput::new()->fromMakerDto(
            $inputDto,
            $configDto
        );
        $result = $validator->validate($validationInput);

        if ($result->hasSuccess()) {
            return;
        }

        $errorCodeMap = [
            SaleNoValidationResult::ERR_SALE_FULL_NO_PARSE_ERROR => ResultCode::SALE_FULL_NO_PARSE_ERROR,
            SaleNoValidationResult::ERR_SALE_NO_EXIST => ResultCode::SALE_NO_EXIST,
            SaleNoValidationResult::ERR_SALE_NUM_EXT_INVALID => ResultCode::SALE_NUM_EXT_INVALID,
            SaleNoValidationResult::ERR_SALE_NUM_EXT_NOT_ALPHA => ResultCode::SALE_NUM_EXT_NOT_ALPHA,
            SaleNoValidationResult::ERR_SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE => ResultCode::SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE,
            SaleNoValidationResult::ERR_SALE_NUM_INVALID => ResultCode::SALE_NUM_INVALID
        ];
        foreach ($errorCodeMap as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $auctionMakerValidator->addError($callerCode);
            }
        }

        if ($result->hasErrorByCode(SaleNoValidationResult::ERR_SALE_FULL_NO_PARSE_ERROR)) {
            $auctionMakerValidator->addError(ResultCode::SALE_FULL_NO_PARSE_ERROR, $result->errorMessage());
        }

        log_debug("Item# validation failed" . composeSuffix($result->logData() + $validationInput->logData()));
    }

}
