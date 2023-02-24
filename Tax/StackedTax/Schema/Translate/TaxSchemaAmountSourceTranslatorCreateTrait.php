<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Translate;

/**
 * Trait TaxSchemaAmountSourceTranslatorCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Translate
 */
trait TaxSchemaAmountSourceTranslatorCreateTrait
{
    protected ?TaxSchemaAmountSourceTranslator $taxSchemaAmountSourceTranslator = null;

    /**
     * @return TaxSchemaAmountSourceTranslator
     */
    protected function createTaxSchemaAmountSourceTranslator(): TaxSchemaAmountSourceTranslator
    {
        return $this->taxSchemaAmountSourceTranslator ?: TaxSchemaAmountSourceTranslator::new();
    }

    /**
     * @param TaxSchemaAmountSourceTranslator $taxSchemaAmountSourceTranslator
     * @return static
     * @internal
     */
    public function setTaxSchemaAmountSourceTranslator(TaxSchemaAmountSourceTranslator $taxSchemaAmountSourceTranslator): static
    {
        $this->taxSchemaAmountSourceTranslator = $taxSchemaAmountSourceTranslator;
        return $this;
    }
}
