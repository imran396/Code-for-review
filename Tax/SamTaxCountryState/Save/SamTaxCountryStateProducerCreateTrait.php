<?php
/**
 * SAM-6424 : Country tax services
 * https://bidpath.atlassian.net/browse/SAM-6424
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 10, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\SamTaxCountryState\Save;

/**
 * Trait SamTaxCountryStateProducerCreateTrait
 * @package Sam\Tax\SamTaxCountryState\Save
 */
trait SamTaxCountryStateProducerCreateTrait
{
    protected ?SamTaxCountryStateProducer $samTaxCountryStateProducer = null;

    /**
     * @return SamTaxCountryStateProducer
     */
    protected function createSamTaxCountryStateProducer(): SamTaxCountryStateProducer
    {
        return $this->samTaxCountryStateProducer ?: SamTaxCountryStateProducer::new();
    }

    /**
     * @param SamTaxCountryStateProducer $samTaxCountryStateProducer
     * @return $this
     * @internal
     */
    public function setSamTaxCountryStateProducer(SamTaxCountryStateProducer $samTaxCountryStateProducer): static
    {
        $this->samTaxCountryStateProducer = $samTaxCountryStateProducer;
        return $this;
    }
}
