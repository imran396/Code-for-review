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

namespace Sam\Tax\StackedTax\Schema\Edit\Save;

/**
 * Trait TaxSchemaProducerCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Edit\Save
 */
trait TaxSchemaProducerCreateTrait
{
    protected ?TaxSchemaProducer $taxSchemaProducer = null;

    /**
     * @return TaxSchemaProducer
     */
    protected function createTaxSchemaProducer(): TaxSchemaProducer
    {
        return $this->taxSchemaProducer ?: TaxSchemaProducer::new();
    }

    /**
     * @param TaxSchemaProducer $taxSchemaProducer
     * @return static
     * @internal
     */
    public function setTaxSchemaProducer(TaxSchemaProducer $taxSchemaProducer): static
    {
        $this->taxSchemaProducer = $taxSchemaProducer;
        return $this;
    }
}
