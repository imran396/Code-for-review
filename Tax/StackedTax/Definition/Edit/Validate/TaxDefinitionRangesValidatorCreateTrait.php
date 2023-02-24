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

namespace Sam\Tax\StackedTax\Definition\Edit\Validate;

/**
 * Trait TaxDefinitionRangesValidatorCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Edit\Validate
 */
trait TaxDefinitionRangesValidatorCreateTrait
{
    protected ?TaxDefinitionRangesValidator $taxDefinitionRangesValidator = null;

    /**
     * @return TaxDefinitionRangesValidator
     */
    protected function createTaxDefinitionRangesValidator(): TaxDefinitionRangesValidator
    {
        return $this->taxDefinitionRangesValidator ?: TaxDefinitionRangesValidator::new();
    }

    /**
     * @param TaxDefinitionRangesValidator $taxDefinitionRangesValidator
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangesValidator(TaxDefinitionRangesValidator $taxDefinitionRangesValidator): static
    {
        $this->taxDefinitionRangesValidator = $taxDefinitionRangesValidator;
        return $this;
    }
}
