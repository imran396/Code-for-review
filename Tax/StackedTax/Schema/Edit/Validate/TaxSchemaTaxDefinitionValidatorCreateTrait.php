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

namespace Sam\Tax\StackedTax\Schema\Edit\Validate;

/**
 * Trait TaxSchemaTaxDefinitionValidatorCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Edit\Validate
 */
trait TaxSchemaTaxDefinitionValidatorCreateTrait
{
    protected ?TaxSchemaTaxDefinitionValidator $taxSchemaTaxDefinitionValidator = null;

    /**
     * @return TaxSchemaTaxDefinitionValidator
     */
    protected function createTaxSchemaTaxDefinitionValidator(): TaxSchemaTaxDefinitionValidator
    {
        return $this->taxSchemaTaxDefinitionValidator ?: TaxSchemaTaxDefinitionValidator::new();
    }

    /**
     * @param TaxSchemaTaxDefinitionValidator $taxSchemaTaxDefinitionValidator
     * @return static
     * @internal
     */
    public function setTaxSchemaTaxDefinitionValidator(TaxSchemaTaxDefinitionValidator $taxSchemaTaxDefinitionValidator): static
    {
        $this->taxSchemaTaxDefinitionValidator = $taxSchemaTaxDefinitionValidator;
        return $this;
    }
}
