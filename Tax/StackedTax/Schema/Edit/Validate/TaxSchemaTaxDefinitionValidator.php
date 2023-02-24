<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Edit\Validate;

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Definition\Load\TaxDefinitionLoaderCreateTrait;
use Sam\Tax\StackedTax\Schema\Edit\Dto\TaxSchemaDto;
use Sam\Tax\StackedTax\Schema\Edit\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Tax\StackedTax\Schema\Edit\Validate\TaxSchemaTaxDefinitionValidationResult as Result;

/**
 * Class TaxSchemaTaxDefinitionValidator
 * @package Sam\Tax\StackedTax\Schema\Edit\Validate
 */
class TaxSchemaTaxDefinitionValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use TaxDefinitionLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(array $taxDefinitionIds, TaxSchemaDto $taxSchemaDto, int $accountId): Result
    {
        $result = Result::new()->construct();
        $taxSchemaTaxDefinitionDtos = $this->createDataProvider()->loadTaxSchemaTaxDefinitions($taxDefinitionIds, $accountId);
        $taxSchemaTaxDefinitionDtos = ArrayHelper::indexEntities($taxSchemaTaxDefinitionDtos, 'taxDefinitionId');
        foreach ($taxDefinitionIds as $definitionId) {
            $taxSchemaTaxDefinitionDto = $taxSchemaTaxDefinitionDtos[$definitionId] ?? null;
            if (!$taxSchemaTaxDefinitionDto) {
                $result->addError(Result::ERR_TAX_DEFINITION_DOES_NOT_EXIST, $definitionId, '');
                continue;
            }

            // Do not validate existing relations
            if ($taxSchemaTaxDefinitionDto->taxSchemaTaxDefinitionId !== null) {
                continue;
            }

            if ($taxSchemaDto->geoType < $taxSchemaTaxDefinitionDto->geoType) {
                $result->addError(Result::ERR_NOT_APPLICABLE_TAX_DEFINITION_GEO_TYPE, $definitionId, $taxSchemaTaxDefinitionDto->name);
            }
        }
        return $result;
    }
}
