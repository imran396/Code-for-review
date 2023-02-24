<?php
/**
 * SAM-10823: Stacked Tax. Location reference with Tax Schema (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LocationEditForm\TaxSchema\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\LocationEditForm\TaxSchema\Load\TaxSchemaDataLoaderCreateTrait;
use Sam\View\Admin\Form\LocationEditForm\TaxSchema\Load\TaxSchemaDto;
use Sam\View\Admin\Form\LocationEditForm\TaxSchema\Validate\LocationSchemaValidatorResult as Result;

/**
 * Class LocationTaxSchemaValidator
 * @package Sam\View\Admin\Form\LocationEditForm\TaxSchema\Validate;
 */
class LocationTaxSchemaValidator extends CustomizableClass
{
    use TaxSchemaDataLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(int $locationId, array $taxSchemaIds, int $systemAccountId): Result
    {
        $result = new Result();
        $taxSchemasDtos = $this->createTaxSchemaDataLoader()->loadById($taxSchemaIds);
        $this->isValidTaxSchema($taxSchemasDtos, $result);
        $this->isValidLocation($locationId, $systemAccountId, $result);
        $this->isOriginal($taxSchemasDtos, $result);
        $this->isDuplicate($locationId, $taxSchemaIds, $result);
        return $result;
    }

    /**
     * @param TaxSchemaDto[] $taxSchemasDtos
     * @return void
     */
    protected function isValidTaxSchema(array $taxSchemasDtos, Result $result): void
    {
        foreach ($taxSchemasDtos as $taxSchemasDto) {
            if (!$taxSchemasDto->active) {
                $result->addError(Result::ERR_INACTIVE);
                break;
            }
        }
    }

    protected function isValidLocation(int $locationId, int $systemAccountId, Result $result): void
    {
        $location = \Location::Load($locationId);
        if ($location->AccountId !== $systemAccountId) {
            $result->addError(Result::ERR_INVALID_LOCATION_ACCOUNT);
        }
    }

    protected function isDuplicate(int $locationId, array $taxSchemaIds, Result $result): void
    {
        foreach ($taxSchemaIds as $taxSchemaId) {
            $hasDuplicate = $this->createTaxSchemaDataLoader()->checkDuplicate($locationId, $taxSchemaId);
            if ($hasDuplicate) {
                $result->addError(Result::ERR_IS_DUPLICATE);
                break;
            }
        }
    }

    protected function isOriginal(array $taxSchemasDtos, Result $result): void
    {
        foreach ($taxSchemasDtos as $taxSchemasDto) {
            if ($taxSchemasDto->sourceTaxSchemaId) {
                $result->addError(Result::ERR_IS_ORIGINAL_TAX_SCHEMA);
                break;
            }
        }
    }
}
