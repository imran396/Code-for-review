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

namespace Sam\Tax\StackedTax\Schema\Edit\Validate\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Schema\Edit\Validate\TaxSchemaTaxDefinitionValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class TaxSchemaTaxDefinitionValidationErrorTranslator
 * @package Sam\Tax\StackedTax\Schema\Edit\Validate\Translate
 */
class TaxSchemaTaxDefinitionValidationErrorTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATIONS = [
        TaxSchemaTaxDefinitionValidationResult::ERR_TAX_DEFINITION_DOES_NOT_EXIST => 'tax.schema.tax_definition.not_exists',
        TaxSchemaTaxDefinitionValidationResult::ERR_NOT_APPLICABLE_TAX_DEFINITION_GEO_TYPE => 'tax.schema.tax_definition.not_applicable_geo_type',
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
