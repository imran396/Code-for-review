<?php
/**
 * SAM-11950: Stacked Tax - Stage 2: Locations and tax authority: Display Geo Taxes in invoice
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 17, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Validate\AuctionLotMakerValidator;
use Sam\EntityMaker\AuctionLot\Validate\Constants\ResultCode;
use Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema\Internal\TaxCountry\AuctionLotTaxCountryDetectorCreateTrait;
use Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema\Internal\Validate\AuctionLotTaxSchemaValidationResult;
use Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema\Internal\Validate\AuctionLotTaxSchemaValidatorCreateTrait;

/**
 * Class AuctionLotTaxSchemaValidationIntegrator
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema
 */
class AuctionLotTaxSchemaValidationIntegrator extends CustomizableClass
{
    use AuctionLotTaxCountryDetectorCreateTrait;
    use AuctionLotTaxSchemaValidatorCreateTrait;

    protected const ERROR_CODE_MAP = [
        AuctionLotTaxSchemaValidationResult::ERR_HP_TAX_SCHEMA_ID_INVALID => ResultCode::HP_TAX_SCHEMA_ID_INVALID,
        AuctionLotTaxSchemaValidationResult::ERR_HP_TAX_SCHEMA_COUNTRY_MISMATCH => ResultCode::HP_TAX_SCHEMA_COUNTRY_MISMATCH,
        AuctionLotTaxSchemaValidationResult::ERR_BP_TAX_SCHEMA_ID_INVALID => ResultCode::BP_TAX_SCHEMA_ID_INVALID,
        AuctionLotTaxSchemaValidationResult::ERR_BP_TAX_SCHEMA_COUNTRY_MISMATCH => ResultCode::BP_TAX_SCHEMA_COUNTRY_MISMATCH,
    ];

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

        $taxCountry = $this->createAuctionLotTaxCountryDetector()->detect(
            $configDto->lotItemTaxDefaultCountryInput,
            Cast::toInt($inputDto->lotItemId),
            Cast::toInt($inputDto->auctionId),
            $configDto->serviceAccountId
        );

        $result = $this->createAuctionLotTaxSchemaValidator()->validate(
            $inputDto->hpTaxSchemaId,
            $inputDto->bpTaxSchemaId,
            $taxCountry,
            $configDto->serviceAccountId,
        );

        foreach (self::ERROR_CODE_MAP as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $auctionLotMakerValidator->addError($callerCode);
            }
        }
    }
}
