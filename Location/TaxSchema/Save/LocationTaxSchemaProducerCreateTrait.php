<?php
/**
 * SAM-10823: Stacked Tax. Location reference with Tax Schema (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Location\TaxSchema\Save;

/**
 * Trait TaxSchemaTaxDefinitionProducerCreateTrait
 * @package namespace Sam\Location\TaxSchema\Save;
 */
trait LocationTaxSchemaProducerCreateTrait
{
    protected ?LocationTaxSchemaProducer $locationTaxSchemaProducer = null;

    /**
     * @return LocationTaxSchemaProducer
     */
    protected function createLocationTaxSchemaProducer(): LocationTaxSchemaProducer
    {
        return $this->locationTaxSchemaProducer ?: LocationTaxSchemaProducer::new();
    }

    /**
     * @param LocationTaxSchemaProducer $locationTaxSchemaProducer
     * @return static
     * @internal
     */
    public function setLocationTaxSchemaProducer(LocationTaxSchemaProducer $locationTaxSchemaProducer): static
    {
        $this->locationTaxSchemaProducer = $locationTaxSchemaProducer;
        return $this;
    }
}
