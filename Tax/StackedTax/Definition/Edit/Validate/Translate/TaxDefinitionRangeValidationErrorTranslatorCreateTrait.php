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

/**
 * Trait TaxDefinitionRangeValidationErrorTranslatorCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Edit\Validate\Translate
 */
trait TaxDefinitionRangeValidationErrorTranslatorCreateTrait
{
    protected ?TaxDefinitionRangeValidationErrorTranslator $taxDefinitionRangeValidationErrorTranslator = null;

    /**
     * @return TaxDefinitionRangeValidationErrorTranslator
     */
    protected function createTaxDefinitionRangeValidationErrorTranslator(): TaxDefinitionRangeValidationErrorTranslator
    {
        return $this->taxDefinitionRangeValidationErrorTranslator ?: TaxDefinitionRangeValidationErrorTranslator::new();
    }

    /**
     * @param TaxDefinitionRangeValidationErrorTranslator $taxDefinitionRangeValidationErrorTranslator
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangeValidationErrorTranslator(TaxDefinitionRangeValidationErrorTranslator $taxDefinitionRangeValidationErrorTranslator): static
    {
        $this->taxDefinitionRangeValidationErrorTranslator = $taxDefinitionRangeValidationErrorTranslator;
        return $this;
    }
}
