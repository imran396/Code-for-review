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

namespace Sam\Tax\StackedTax\Definition\Delete;

/**
 * Trait TaxDefinitionDeleterCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Delete
 */
trait TaxDefinitionDeleterCreateTrait
{
    protected ?TaxDefinitionDeleter $taxDefinitionDeleter = null;

    /**
     * @return TaxDefinitionDeleter
     */
    protected function createTaxDefinitionDeleter(): TaxDefinitionDeleter
    {
        return $this->taxDefinitionDeleter ?: TaxDefinitionDeleter::new();
    }

    /**
     * @param TaxDefinitionDeleter $taxDefinitionDeleter
     * @return static
     * @internal
     */
    public function setTaxDefinitionDeleter(TaxDefinitionDeleter $taxDefinitionDeleter): static
    {
        $this->taxDefinitionDeleter = $taxDefinitionDeleter;
        return $this;
    }
}
