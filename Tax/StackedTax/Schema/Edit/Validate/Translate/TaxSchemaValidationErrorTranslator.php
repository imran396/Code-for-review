<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Edit\Validate\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Schema\Edit\Validate\TaxSchemaValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class TaxSchemaValidationErrorTranslator
 * @package Sam\Tax\StackedTax\Schema\Edit\Validate\Translate
 */
class TaxSchemaValidationErrorTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATIONS = [
        TaxSchemaValidationResult::ERR_CITY_NOT_ALLOWED => 'tax.schema.city.not_allowed',
        TaxSchemaValidationResult::ERR_CITY_REQUIRED => 'tax.schema.city.required',
        TaxSchemaValidationResult::ERR_COUNTRY_INVALID => 'tax.schema.country.invalid',
        TaxSchemaValidationResult::ERR_COUNTRY_REQUIRED => 'tax.schema.country.required',
        TaxSchemaValidationResult::ERR_COUNTY_NOT_ALLOWED => 'tax.schema.county.not_allowed',
        TaxSchemaValidationResult::ERR_COUNTY_REQUIRED => 'tax.schema.county.required',
        TaxSchemaValidationResult::ERR_COUNTRY_NOT_ALLOWED => 'tax.schema.county.not_allowed',
        TaxSchemaValidationResult::ERR_GEO_TYPE_INVALID => 'tax.schema.geo_type.invalid',
        TaxSchemaValidationResult::ERR_NAME_EXISTS => 'tax.schema.name.exists',
        TaxSchemaValidationResult::ERR_NAME_REQUIRED => 'tax.schema.name.required',
        TaxSchemaValidationResult::ERR_STATE_NOT_ALLOWED => 'tax.schema.state.not_allowed',
        TaxSchemaValidationResult::ERR_STATE_REQUIRED => 'tax.schema.state.required',
        TaxSchemaValidationResult::ERR_TAX_SCHEMA_NOT_FOUND => 'tax.schema.not_found',
        TaxSchemaValidationResult::ERR_AMOUNT_SOURCE_INVALID => 'tax.schema.amount_source.invalid',
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
