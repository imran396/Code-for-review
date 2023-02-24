<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Edit\Validate\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Definition\Edit\Validate\TaxDefinitionValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class TaxDefinitionValidationErrorTranslator
 * @package Sam\Tax\StackedTax\Definition\Edit\Validate\Translate
 */
class TaxDefinitionValidationErrorTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATIONS = [
        TaxDefinitionValidationResult::ERR_CITY_NOT_ALLOWED => 'tax_definition.city.not_allowed',
        TaxDefinitionValidationResult::ERR_CITY_REQUIRED => 'tax_definition.city.required',
        TaxDefinitionValidationResult::ERR_COUNTRY_INVALID => 'tax_definition.country.invalid',
        TaxDefinitionValidationResult::ERR_COUNTRY_REQUIRED => 'tax_definition.country.required',
        TaxDefinitionValidationResult::ERR_COUNTY_NOT_ALLOWED => 'tax_definition.county.not_allowed',
        TaxDefinitionValidationResult::ERR_COUNTY_REQUIRED => 'tax_definition.county.required',
        TaxDefinitionValidationResult::ERR_COUNTRY_NOT_ALLOWED => 'tax_definition.county.not_allowed',
        TaxDefinitionValidationResult::ERR_GEO_TYPE_INVALID => 'tax_definition.geo_type.invalid',
        TaxDefinitionValidationResult::ERR_NAME_EXISTS => 'tax_definition.name.exists',
        TaxDefinitionValidationResult::ERR_NAME_REQUIRED => 'tax_definition.name.required',
        TaxDefinitionValidationResult::ERR_STATE_NOT_ALLOWED => 'tax_definition.state.not_allowed',
        TaxDefinitionValidationResult::ERR_STATE_REQUIRED => 'tax_definition.state.required',
        TaxDefinitionValidationResult::ERR_TAX_DEFINITION_NOT_FOUND => 'tax_definition.not_found',
        TaxDefinitionValidationResult::ERR_TAX_TYPE_INVALID => 'tax_definition.tax_type.invalid',
        TaxDefinitionValidationResult::ERR_TAX_TYPE_REQUIRED => 'tax_definition.tax_type.required',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function trans(int $errorCode): string
    {
        if (!array_key_exists($errorCode, self::TRANSLATIONS)) {
            return '';
        }
        return $this->getAdminTranslator()->trans(self::TRANSLATIONS[$errorCode], [], 'admin_validation');
    }
}
