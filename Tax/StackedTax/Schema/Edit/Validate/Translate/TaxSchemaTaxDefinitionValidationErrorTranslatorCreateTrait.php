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

/**
 * Trait TaxSchemaTaxDefinitionValidationErrorTranslatorCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Edit\Validate\Translate
 */
trait TaxSchemaTaxDefinitionValidationErrorTranslatorCreateTrait
{
    protected ?TaxSchemaTaxDefinitionValidationErrorTranslator $taxSchemaTaxDefinitionValidationErrorTranslator = null;

    /**
     * @return TaxSchemaTaxDefinitionValidationErrorTranslator
     */
    protected function createTaxSchemaTaxDefinitionValidationErrorTranslator(): TaxSchemaTaxDefinitionValidationErrorTranslator
    {
        return $this->taxSchemaTaxDefinitionValidationErrorTranslator ?: TaxSchemaTaxDefinitionValidationErrorTranslator::new();
    }

    /**
     * @param TaxSchemaTaxDefinitionValidationErrorTranslator $taxSchemaTaxDefinitionValidationErrorTranslator
     * @return static
     * @internal
     */
    public function setTaxSchemaTaxDefinitionValidationErrorTranslator(TaxSchemaTaxDefinitionValidationErrorTranslator $taxSchemaTaxDefinitionValidationErrorTranslator): static
    {
        $this->taxSchemaTaxDefinitionValidationErrorTranslator = $taxSchemaTaxDefinitionValidationErrorTranslator;
        return $this;
    }
}
