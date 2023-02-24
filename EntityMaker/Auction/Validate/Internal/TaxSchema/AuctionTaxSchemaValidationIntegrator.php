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

namespace Sam\EntityMaker\Auction\Validate\Internal\TaxSchema;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\AuctionMakerValidator;
use Sam\EntityMaker\Auction\Validate\Constants\ResultCode;
use Sam\EntityMaker\Auction\Validate\Internal\TaxSchema\Internal\TaxCountry\AuctionTaxCountryDetectorCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\TaxSchema\Internal\Validate\AuctionTaxSchemaValidationResult;
use Sam\EntityMaker\Auction\Validate\Internal\TaxSchema\Internal\Validate\AuctionTaxSchemaValidatorCreateTrait;

class AuctionTaxSchemaValidationIntegrator extends CustomizableClass
{
    use AuctionTaxCountryDetectorCreateTrait;
    use AuctionTaxSchemaValidatorCreateTrait;

    protected const ERROR_CODE_MAP = [
        AuctionTaxSchemaValidationResult::ERR_HP_TAX_SCHEMA_ID_INVALID => ResultCode::HP_TAX_SCHEMA_ID_INVALID,
        AuctionTaxSchemaValidationResult::ERR_HP_TAX_SCHEMA_COUNTRY_MISMATCH => ResultCode::HP_TAX_SCHEMA_COUNTRY_MISMATCH,
        AuctionTaxSchemaValidationResult::ERR_BP_TAX_SCHEMA_ID_INVALID => ResultCode::BP_TAX_SCHEMA_ID_INVALID,
        AuctionTaxSchemaValidationResult::ERR_BP_TAX_SCHEMA_COUNTRY_MISMATCH => ResultCode::BP_TAX_SCHEMA_COUNTRY_MISMATCH,
        AuctionTaxSchemaValidationResult::ERR_SERVICES_TAX_SCHEMA_ID_INVALID => ResultCode::SERVICES_TAX_SCHEMA_ID_INVALID,
        AuctionTaxSchemaValidationResult::ERR_SERVICES_TAX_SCHEMA_COUNTRY_MISMATCH => ResultCode::SERVICES_TAX_SCHEMA_COUNTRY_MISMATCH,
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
     * @param AuctionMakerValidator $auctionMakerValidator
     * @return void
     */
    public function validate(AuctionMakerValidator $auctionMakerValidator): void
    {
        $inputDto = $auctionMakerValidator->getInputDto();
        $configDto = $auctionMakerValidator->getConfigDto();

        $taxCountry = $this->createAuctionTaxCountryDetector()->detect(
            isset($inputDto->taxDefaultCountry) ? (string)$inputDto->taxDefaultCountry : null,
            Cast::toInt($inputDto->id),
            $configDto->serviceAccountId
        );

        $result = $this->createAuctionTaxSchemaValidator()->validate(
            $inputDto->hpTaxSchemaId,
            $inputDto->bpTaxSchemaId,
            $inputDto->servicesTaxSchemaId,
            $taxCountry,
            $configDto->serviceAccountId,
        );

        foreach (self::ERROR_CODE_MAP as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $auctionMakerValidator->addError($callerCode);
            }
        }
    }
}
