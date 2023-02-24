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
 * Trait TaxDefinitionValidationErrorTranslatorCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Edit\Validate\Translate
 */
trait TaxDefinitionValidationErrorTranslatorCreateTrait
{
    protected ?TaxDefinitionValidationErrorTranslator $taxDefinitionValidationErrorTranslator = null;

    /**
     * @return TaxDefinitionValidationErrorTranslator
     */
    protected function createTaxDefinitionValidationErrorTranslator(): TaxDefinitionValidationErrorTranslator
    {
        return $this->taxDefinitionValidationErrorTranslator ?: TaxDefinitionValidationErrorTranslator::new();
    }

    /**
     * @param TaxDefinitionValidationErrorTranslator $taxDefinitionValidationErrorTranslator
     * @return static
     * @internal
     */
    public function setTaxDefinitionValidationErrorTranslator(TaxDefinitionValidationErrorTranslator $taxDefinitionValidationErrorTranslator): static
    {
        $this->taxDefinitionValidationErrorTranslator = $taxDefinitionValidationErrorTranslator;
        return $this;
    }
}
