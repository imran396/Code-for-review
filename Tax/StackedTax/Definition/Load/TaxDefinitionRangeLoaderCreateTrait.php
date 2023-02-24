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

namespace Sam\Tax\StackedTax\Definition\Load;

/**
 * Trait TaxDefinitionRangeLoaderCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Load
 */
trait TaxDefinitionRangeLoaderCreateTrait
{
    protected ?TaxDefinitionRangeLoader $taxDefinitionRangeLoader = null;

    /**
     * @return TaxDefinitionRangeLoader
     */
    protected function createTaxDefinitionRangeLoader(): TaxDefinitionRangeLoader
    {
        return $this->taxDefinitionRangeLoader ?: TaxDefinitionRangeLoader::new();
    }

    /**
     * @param TaxDefinitionRangeLoader $taxDefinitionRangeLoader
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangeLoader(TaxDefinitionRangeLoader $taxDefinitionRangeLoader): static
    {
        $this->taxDefinitionRangeLoader = $taxDefinitionRangeLoader;
        return $this;
    }
}
