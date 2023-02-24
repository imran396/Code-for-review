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
use Sam\Tax\StackedTax\Definition\Edit\Validate\TaxDefinitionRangesValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class TaxDefinitionRangeValidationErrorTranslator
 * @package Sam\Tax\StackedTax\Definition\Edit\Validate\Translate
 */
class TaxDefinitionRangeValidationErrorTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATIONS = [
        TaxDefinitionRangesValidationResult::ERR_RANGE_AMOUNT_INVALID => 'tax_definition.range.amount.invalid',
        TaxDefinitionRangesValidationResult::ERR_RANGE_AMOUNT_REQUIRED => 'tax_definition.range.amount.required',
        TaxDefinitionRangesValidationResult::ERR_RANGE_EXIST => 'tax_definition.range.amount.exist',
        TaxDefinitionRangesValidationResult::ERR_RANGE_FIRST_AMOUNT_MUST_BE_ZERO => 'tax_definition.range.amount.first_amount_must_be_zero',
        TaxDefinitionRangesValidationResult::ERR_RANGE_FIXED_INVALID => 'tax_definition.range.fixed.invalid',
        TaxDefinitionRangesValidationResult::ERR_RANGE_FIXED_REQUIRED => 'tax_definition.range.fixed.required',
        TaxDefinitionRangesValidationResult::ERR_RANGE_MODE_INVALID => 'tax_definition.range.mode.invalid',
        TaxDefinitionRangesValidationResult::ERR_RANGE_MODE_REQUIRED => 'tax_definition.range.mode.required',
        TaxDefinitionRangesValidationResult::ERR_RANGE_PERCENT_INVALID => 'tax_definition.range.percent.invalid',
        TaxDefinitionRangesValidationResult::ERR_RANGE_PERCENT_REQUIRED => 'tax_definition.range.percent.required',
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
