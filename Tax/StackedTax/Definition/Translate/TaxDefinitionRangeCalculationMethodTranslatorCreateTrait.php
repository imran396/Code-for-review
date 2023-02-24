<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Translate;

/**
 * Trait TaxDefinitionRangeCalculationMethodTranslatorCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Translate
 */
trait TaxDefinitionRangeCalculationMethodTranslatorCreateTrait
{
    protected ?TaxDefinitionRangeCalculationMethodTranslator $taxDefinitionRangeCalculationMethodTranslator = null;

    /**
     * @return TaxDefinitionRangeCalculationMethodTranslator
     */
    protected function createTaxDefinitionRangeCalculationMethodTranslator(): TaxDefinitionRangeCalculationMethodTranslator
    {
        return $this->taxDefinitionRangeCalculationMethodTranslator ?: TaxDefinitionRangeCalculationMethodTranslator::new();
    }

    /**
     * @param TaxDefinitionRangeCalculationMethodTranslator $taxDefinitionRangeCalculationMethodTranslator
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangeCalculationMethodTranslator(TaxDefinitionRangeCalculationMethodTranslator $taxDefinitionRangeCalculationMethodTranslator): static
    {
        $this->taxDefinitionRangeCalculationMethodTranslator = $taxDefinitionRangeCalculationMethodTranslator;
        return $this;
    }
}
