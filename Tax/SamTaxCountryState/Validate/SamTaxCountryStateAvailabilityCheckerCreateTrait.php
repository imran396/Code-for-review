<?php
/**
 * SAM-6424 : Country tax services
 * https://bidpath.atlassian.net/browse/SAM-6424
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\SamTaxCountryState\Validate;

/**
 * Trait SamTaxCountryStateAvailabilityCheckerCreateTrait
 * @package Sam\Tax\SamTaxCountryState\Validate
 */
trait SamTaxCountryStateAvailabilityCheckerCreateTrait
{
    protected ?SamTaxCountryStateAvailabilityChecker $samTaxCountryStateAvailabilityChecker = null;

    /**
     * @return SamTaxCountryStateAvailabilityChecker
     */
    protected function createSamTaxCountryStateAvailabilityChecker(): SamTaxCountryStateAvailabilityChecker
    {
        return $this->samTaxCountryStateAvailabilityChecker ?: SamTaxCountryStateAvailabilityChecker::new();
    }

    /**
     * @param SamTaxCountryStateAvailabilityChecker $samTaxCountryStateAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setSamTaxCountryStateAvailabilityChecker(
        SamTaxCountryStateAvailabilityChecker $samTaxCountryStateAvailabilityChecker
    ): static {
        $this->samTaxCountryStateAvailabilityChecker = $samTaxCountryStateAvailabilityChecker;
        return $this;
    }
}
