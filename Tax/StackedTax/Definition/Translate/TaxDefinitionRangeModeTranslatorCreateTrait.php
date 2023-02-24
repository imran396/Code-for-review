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
 * Trait TaxDefinitionRangeModeTranslatorCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Translate
 */
trait TaxDefinitionRangeModeTranslatorCreateTrait
{
    protected ?TaxDefinitionRangeModeTranslator $taxDefinitionRangeModeTranslator = null;

    /**
     * @return TaxDefinitionRangeModeTranslator
     */
    protected function createTaxDefinitionRangeModeTranslator(): TaxDefinitionRangeModeTranslator
    {
        return $this->taxDefinitionRangeModeTranslator ?: TaxDefinitionRangeModeTranslator::new();
    }

    /**
     * @param TaxDefinitionRangeModeTranslator $taxDefinitionRangeModeTranslator
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangeModeTranslator(TaxDefinitionRangeModeTranslator $taxDefinitionRangeModeTranslator): static
    {
        $this->taxDefinitionRangeModeTranslator = $taxDefinitionRangeModeTranslator;
        return $this;
    }
}
