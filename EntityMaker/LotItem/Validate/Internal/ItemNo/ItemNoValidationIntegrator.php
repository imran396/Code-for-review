<?php
/**
 * SAM-8833: Lot item entity maker - extract item# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\ItemNo;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Validate\Constants\ResultCode;
use Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate\ItemNoValidationInput;
use Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate\ItemNoValidationResult;
use Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate\ItemNoValidatorCreateTrait;
use Sam\EntityMaker\LotItem\Validate\LotItemMakerValidator;

/**
 * Class ItemNoValidationIntegrator
 * @package Sam\EntityMaker\LotItem
 */
class ItemNoValidationIntegrator extends CustomizableClass
{
    use ItemNoValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemMakerValidator $lotItemMakerValidator
     * @return void
     */
    public function validate(LotItemMakerValidator $lotItemMakerValidator): void
    {
        $inputDto = $lotItemMakerValidator->getInputDto();
        $configDto = $lotItemMakerValidator->getConfigDto();
        $validator = $this->createItemNoValidator()->construct();

        $validationInput = ItemNoValidationInput::new()->fromMakerDto(
            $inputDto,
            $configDto
        );
        $result = $validator->validate($validationInput);

        if ($result->hasSuccess()) {
            return;
        }

        $errorCodeMap = [
            ItemNoValidationResult::ERR_ITEM_NUM_REQUIRED => ResultCode::ITEM_NUM_REQUIRED,
            ItemNoValidationResult::ERR_ITEM_NUM_NOT_POSITIVE_INTEGER => ResultCode::ITEM_NUM_INVALID_FORMAT,
            ItemNoValidationResult::ERR_ITEM_NUM_EXCEED_MAX_INTEGER => ResultCode::ITEM_NUM_HIGHER_MAX_AVAILABLE_VALUE,
            ItemNoValidationResult::ERR_ITEM_NUM_EXTENSION_NOT_ALPHA_NUMERIC => ResultCode::ITEM_NUM_EXT_INVALID_FORMAT,
            ItemNoValidationResult::ERR_NOT_UNIQUE => ResultCode::ITEM_NUM_ALREADY_EXIST,
        ];
        foreach ($errorCodeMap as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $lotItemMakerValidator->addError($callerCode);
            }
        }

        if ($result->hasErrorByCode(ItemNoValidationResult::ERR_CONCATENATED_ITEM_NO_PARSE_FAILED)) {
            $lotItemMakerValidator->addError(ResultCode::ITEM_FULL_NUM_PARSE_ERROR, $result->errorMessage());
        }

        if ($result->hasErrorByCode(ItemNoValidationResult::ERR_ITEM_NUM_EXTENSION_INVALID_LENGTH)) {
            $tpl = $lotItemMakerValidator->getErrorMessage(ResultCode::ITEM_NUM_EXT_INVALID_LENGTH);
            $message = sprintf($tpl, $result->itemNumExtensionMaxLength);
            $lotItemMakerValidator->addError(ResultCode::ITEM_NUM_EXT_INVALID_LENGTH, $message);
        }

        log_debug("Item# validation failed" . composeSuffix($result->logData() + $validationInput->logData()));
    }

}
