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

namespace Sam\Tax\StackedTax\Definition\Edit\Normalize;

/**
 * Trait TaxDefinitionRangeNormalizerCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Edit\Normalize
 */
trait TaxDefinitionRangeNormalizerCreateTrait
{
    protected ?TaxDefinitionRangeNormalizer $taxDefinitionRangeNormalizer = null;

    /**
     * @return TaxDefinitionRangeNormalizer
     */
    protected function createTaxDefinitionRangeNormalizer(): TaxDefinitionRangeNormalizer
    {
        return $this->taxDefinitionRangeNormalizer ?: TaxDefinitionRangeNormalizer::new();
    }

    /**
     * @param TaxDefinitionRangeNormalizer $taxDefinitionRangeNormalizer
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangeNormalizer(TaxDefinitionRangeNormalizer $taxDefinitionRangeNormalizer): static
    {
        $this->taxDefinitionRangeNormalizer = $taxDefinitionRangeNormalizer;
        return $this;
    }
}
