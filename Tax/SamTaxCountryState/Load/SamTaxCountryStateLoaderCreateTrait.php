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

namespace Sam\Tax\SamTaxCountryState\Load;

/**
 * Trait SamTaxCountryStateLoaderCreateTrait
 * @package Sam\Tax\SamTaxCountryState\Load
 */
trait SamTaxCountryStateLoaderCreateTrait
{
    protected ?SamTaxCountryStateLoader $samTaxCountryStateLoader = null;

    /**
     * @return SamTaxCountryStateLoader
     */
    protected function createSamTaxCountryStateLoader(): SamTaxCountryStateLoader
    {
        return $this->samTaxCountryStateLoader ?: SamTaxCountryStateLoader::new();
    }

    /**
     * @param SamTaxCountryStateLoader $samTaxCountryStateLoader
     * @return $this
     * @internal
     */
    public function setSamTaxCountryStateLoader(SamTaxCountryStateLoader $samTaxCountryStateLoader): static
    {
        $this->samTaxCountryStateLoader = $samTaxCountryStateLoader;
        return $this;
    }
}
