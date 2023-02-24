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

namespace Sam\EntityMaker\LotItem\Validate\Internal\TaxSchema;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Validate\Constants\ResultCode;
use Sam\EntityMaker\LotItem\Validate\Internal\TaxSchema\Internal\TaxCountry\LotItemTaxCountryDetectorCreateTrait;
use Sam\EntityMaker\LotItem\Validate\Internal\TaxSchema\Internal\Validate\LotItemTaxSchemaValidationResult;
use Sam\EntityMaker\LotItem\Validate\Internal\TaxSchema\Internal\Validate\LotItemTaxSchemaValidatorCreateTrait;
use Sam\EntityMaker\LotItem\Validate\LotItemMakerValidator;

class LotItemTaxSchemaValidationIntegrator extends CustomizableClass
{
    use LotItemTaxCountryDetectorCreateTrait;
    use LotItemTaxSchemaValidatorCreateTrait;

    protected const ERROR_CODE_MAP = [
        LotItemTaxSchemaValidationResult::ERR_HP_TAX_SCHEMA_ID_INVALID => ResultCode::HP_TAX_SCHEMA_ID_INVALID,
        LotItemTaxSchemaValidationResult::ERR_HP_TAX_SCHEMA_COUNTRY_MISMATCH => ResultCode::HP_TAX_SCHEMA_COUNTRY_MISMATCH,
        LotItemTaxSchemaValidationResult::ERR_BP_TAX_SCHEMA_ID_INVALID => ResultCode::BP_TAX_SCHEMA_ID_INVALID,
        LotItemTaxSchemaValidationResult::ERR_BP_TAX_SCHEMA_COUNTRY_MISMATCH => ResultCode::BP_TAX_SCHEMA_COUNTRY_MISMATCH,
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
     * @param LotItemMakerValidator $lotItemMakerValidator
     * @return void
     */
    public function validate(LotItemMakerValidator $lotItemMakerValidator): void
    {
        $inputDto = $lotItemMakerValidator->getInputDto();
        $configDto = $lotItemMakerValidator->getConfigDto();

        $taxCountry = $this->createLotItemTaxCountryDetector()->detect(
            isset($inputDto->taxDefaultCountry) ? (string)$inputDto->taxDefaultCountry : null,
            Cast::toInt($inputDto->id),
            $configDto->auctionId,
            $configDto->serviceAccountId
        );

        $result = $this->createLotItemTaxSchemaValidator()->validate(
            $inputDto->hpTaxSchemaId,
            $inputDto->bpTaxSchemaId,
            $taxCountry,
            $configDto->serviceAccountId,
        );

        foreach (self::ERROR_CODE_MAP as $calleeCode => $callerCode) {
            if ($result->hasErrorByCode($calleeCode)) {
                $lotItemMakerValidator->addError($callerCode);
            }
        }
    }
}
