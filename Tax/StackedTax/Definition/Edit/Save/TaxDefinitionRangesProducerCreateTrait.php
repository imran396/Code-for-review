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

namespace Sam\Tax\StackedTax\Definition\Edit\Save;

/**
 * Trait TaxDefinitionRangesProducerCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Edit\Save
 */
trait TaxDefinitionRangesProducerCreateTrait
{
    protected ?TaxDefinitionRangesProducer $taxDefinitionRangesProducer = null;

    /**
     * @return TaxDefinitionRangesProducer
     */
    protected function createTaxDefinitionRangesProducer(): TaxDefinitionRangesProducer
    {
        return $this->taxDefinitionRangesProducer ?: TaxDefinitionRangesProducer::new();
    }

    /**
     * @param TaxDefinitionRangesProducer $taxDefinitionRangesProducer
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangesProducer(TaxDefinitionRangesProducer $taxDefinitionRangesProducer): static
    {
        $this->taxDefinitionRangesProducer = $taxDefinitionRangesProducer;
        return $this;
    }
}
