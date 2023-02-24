<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Validate\AuctionLotMakerValidator;
use Sam\EntityMaker\AuctionLot\Validate\Constants\ResultCode;
use Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Internal\Validate\QuantityValidationInput;
use Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Internal\Validate\QuantityValidatorCreateTrait;

/**
 * Class QuantityValidationIntegrator
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity
 */
class QuantityValidationIntegrator extends CustomizableClass
{
    use QuantityValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionLotMakerValidator $auctionLotMakerValidator
     * @return void
     */
    public function validate(AuctionLotMakerValidator $auctionLotMakerValidator, int $quantityScale): void
    {
        $inputDto = $auctionLotMakerValidator->getInputDto();
        $configDto = $auctionLotMakerValidator->getConfigDto();

        $validationInput = QuantityValidationInput::new()->fromMakerDto($inputDto, $configDto, $quantityScale);
        $result = $this->createQuantityValidator()->validate($validationInput);

        if ($result->hasError()) {
            $auctionLotMakerValidator->addError(ResultCode::QUANTITY_INVALID, $result->errorMessage());
            log_debug("Quantity validation failed" . composeSuffix(array_merge($result->logData(), $validationInput->logData())));
        }
    }
}
