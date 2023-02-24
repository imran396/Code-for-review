<?php
/**
 * SAM-11950: Stacked Tax - Stage 2: Locations and tax authority: Display Geo Taxes in invoice
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 16, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema\Internal\Validate;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;
use Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema\Internal\Validate\AuctionLotTaxSchemaValidationResult as Result;

class AuctionLotTaxSchemaValidator extends CustomizableClass
{
    use TaxSchemaLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(
        ?int $hpTaxSchemaId,
        ?int $bpTaxSchemaId,
        string $taxCountry,
        int $accountId
    ): Result
    {
        $result = Result::new()->construct();

        if (
            !$hpTaxSchemaId
            && !$bpTaxSchemaId
        ) {
            return $result->addSuccess(Result::OK_NOTHING_TO_VALIDATE);
        }

        $result = $this->validateForAmountSource(
            Constants\StackedTax::AS_HAMMER_PRICE,
            $hpTaxSchemaId,
            $taxCountry,
            $accountId,
            $result
        );

        $result = $this->validateForAmountSource(
            Constants\StackedTax::AS_BUYERS_PREMIUM,
            $bpTaxSchemaId,
            $taxCountry,
            $accountId,
            $result
        );

        if (!$result->hasError()) {
            $result->addSuccess(Result::OK_VALIDATED);
        }

        return $result;
    }

    protected function validateForAmountSource(
        int $amountSource,
        ?int $taxSchemaId,
        string $taxCountry,
        int $accountId,
        Result $result
    ): Result
    {
        if (!$taxSchemaId) {
            return $result;
        }

        $row = $this->createTaxSchemaLoader()->loadSelected(
            ['id', 'country'],
            $taxSchemaId,
            $accountId,
            $amountSource
        );
        $hasTaxSchema = Cast::toInt($row['id'] ?? null) === $taxSchemaId;
        if (!$hasTaxSchema) {
            if ($amountSource === Constants\StackedTax::AS_HAMMER_PRICE) {
                $result->addError(Result::ERR_HP_TAX_SCHEMA_ID_INVALID);
            } else {
                $result->addError(Result::ERR_BP_TAX_SCHEMA_ID_INVALID);
            }
            return $result;
        }

        if (
            $taxCountry
            && $taxCountry !== $row['country']
        ) {
            if ($amountSource === Constants\StackedTax::AS_HAMMER_PRICE) {
                $result->addError(Result::ERR_HP_TAX_SCHEMA_COUNTRY_MISMATCH);
            } else {
                $result->addError(Result::ERR_BP_TAX_SCHEMA_COUNTRY_MISMATCH);
            }
            return $result;
        }

        return $result;
    }
}
