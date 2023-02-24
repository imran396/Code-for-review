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

namespace Sam\Tax\StackedTax\Definition\Edit\Save;

/**
 * Trait TaxDefinitionProducerCreateTrait
 * @package Sam\Tax\StackedTax\Definition\Edit\Save
 */
trait TaxDefinitionProducerCreateTrait
{
    protected ?TaxDefinitionProducer $taxDefinitionProducer = null;

    /**
     * @return TaxDefinitionProducer
     */
    protected function createTaxDefinitionProducer(): TaxDefinitionProducer
    {
        return $this->taxDefinitionProducer ?: TaxDefinitionProducer::new();
    }

    /**
     * @param TaxDefinitionProducer $taxDefinitionProducer
     * @return static
     * @internal
     */
    public function setTaxDefinitionProducer(TaxDefinitionProducer $taxDefinitionProducer): static
    {
        $this->taxDefinitionProducer = $taxDefinitionProducer;
        return $this;
    }
}
