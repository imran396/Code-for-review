<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Edit\Validate;

use Sam\Tax\StackedTax\GeoType\Config\StackedTaxGeoTypeConfigProvider;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Definition\Edit\Dto\TaxDefinitionDto;
use Sam\Tax\StackedTax\Definition\Edit\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Tax\StackedTax\Definition\Edit\Validate\TaxDefinitionValidationResult as Result;

/**
 * Class TaxDefinitionValidator
 * @package Sam\Tax\StackedTax\Definition\Edit
 */
class TaxDefinitionValidator extends CustomizableClass
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

    public function validate(TaxDefinitionDto $dto, ?int $taxDefinitionId, int $accountId): Result
    {
        $result = Result::new()->construct();
        if (
            $taxDefinitionId
            && !$this->createDataProvider()->isTaxDefinitionExists($taxDefinitionId)
        ) {
            $result->addError(Result::ERR_TAX_DEFINITION_NOT_FOUND);
        }

        if ($dto->name === '') {
            $result->addError(Result::ERR_NAME_REQUIRED);
        } elseif (!$this->createDataProvider()->isNameUnique($dto->name, $taxDefinitionId, $accountId)) {
            $result->addError(Result::ERR_NAME_EXISTS);
        }

        if ($dto->taxType === null) {
            $result->addError(Result::ERR_TAX_TYPE_REQUIRED);
        } elseif (!in_array($dto->taxType, Constants\StackedTax::TAX_TYPES, true)) {
            $result->addError(Result::ERR_TAX_TYPE_INVALID);
        }

        if ($dto->country !== '') {
            if (!$this->isLocationFieldAllowed($dto->country, Constants\StackedTax::GT_COUNTRY, $dto->geoType)) {
                $result->addError(Result::ERR_COUNTRY_NOT_ALLOWED);
            }
            if (!array_key_exists($dto->country, Constants\Country::$names)) {
                $result->addError(Result::ERR_COUNTRY_INVALID);
            }
        } elseif ($this->isLocationFieldRequired($dto->country, Constants\StackedTax::GT_COUNTRY, $dto->geoType)) {
            $result->addError(Result::ERR_COUNTRY_REQUIRED);
        }

        if (
            $dto->geoType
            && $dto->country !== ''
            && !StackedTaxGeoTypeConfigProvider::new()->isAvailable($dto->country, $dto->geoType)
        ) {
            $result->addError(Result::ERR_GEO_TYPE_INVALID);
        }

        if (
            $dto->state !== ''
            && !$this->isLocationFieldAllowed($dto->country, Constants\StackedTax::GT_STATE, $dto->geoType)
        ) {
            $result->addError(Result::ERR_STATE_NOT_ALLOWED);
        } elseif (
            $dto->state === ''
            && $this->isLocationFieldRequired($dto->country, Constants\StackedTax::GT_STATE, $dto->geoType)
        ) {
            $result->addError(Result::ERR_STATE_REQUIRED);
        }

        if (
            $dto->county !== ''
            && !$this->isLocationFieldAllowed($dto->country, Constants\StackedTax::GT_COUNTY, $dto->geoType)
        ) {
            $result->addError(Result::ERR_COUNTY_NOT_ALLOWED);
        } elseif (
            $dto->county === ''
            && $this->isLocationFieldRequired($dto->country, Constants\StackedTax::GT_COUNTY, $dto->geoType)
        ) {
            $result->addError(Result::ERR_COUNTY_REQUIRED);
        }

        if (
            $dto->city !== ''
            && !$this->isLocationFieldAllowed($dto->country, Constants\StackedTax::GT_CITY, $dto->geoType)
        ) {
            $result->addError(Result::ERR_CITY_NOT_ALLOWED);
        } elseif (
            $dto->city === ''
            && $this->isLocationFieldRequired($dto->country, Constants\StackedTax::GT_CITY, $dto->geoType)
        ) {
            $result->addError(Result::ERR_CITY_REQUIRED);
        }
        return $result;
    }

    protected function isLocationFieldAllowed(string $country, int $fieldGeoType, ?int $taxDefinitionGeoType): bool
    {
        if ($taxDefinitionGeoType < $fieldGeoType) {
            return false;
        }

        return StackedTaxGeoTypeConfigProvider::new()->isAvailable($country, $fieldGeoType);
    }

    protected function isLocationFieldRequired(string $country, int $fieldGeoType, ?int $taxDefinitionGeoType): bool
    {
        if (!$this->isLocationFieldAllowed($country, $fieldGeoType, $taxDefinitionGeoType)) {
            return false;
        }

        if ($taxDefinitionGeoType === $fieldGeoType) {
            return true;
        }

        return StackedTaxGeoTypeConfigProvider::new()->isRequired($country, $fieldGeoType);
    }
}
