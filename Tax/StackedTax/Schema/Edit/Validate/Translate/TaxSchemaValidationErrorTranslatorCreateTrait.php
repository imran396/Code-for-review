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

/**
 * Trait TaxSchemaValidationErrorTranslatorCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Edit\Validate\Translate
 */
trait TaxSchemaValidationErrorTranslatorCreateTrait
{
    protected ?TaxSchemaValidationErrorTranslator $taxSchemaValidationErrorTranslator = null;

    /**
     * @return TaxSchemaValidationErrorTranslator
     */
    protected function createTaxSchemaValidationErrorTranslator(): TaxSchemaValidationErrorTranslator
    {
        return $this->taxSchemaValidationErrorTranslator ?: TaxSchemaValidationErrorTranslator::new();
    }

    /**
     * @param TaxSchemaValidationErrorTranslator $taxSchemaValidationErrorTranslator
     * @return static
     * @internal
     */
    public function setTaxSchemaValidationErrorTranslator(TaxSchemaValidationErrorTranslator $taxSchemaValidationErrorTranslator): static
    {
        $this->taxSchemaValidationErrorTranslator = $taxSchemaValidationErrorTranslator;
        return $this;
    }
}
