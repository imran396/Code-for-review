<?php
/**
 * SAM-6424 : Country tax services
 * https://bidpath.atlassian.net/browse/SAM-6424
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\SamTaxCountryState\Validate;

/**
 * Trait SamTaxCountryStateExistenceCheckerCreateTrait
 * @package Sam\Tax\SamTaxCountryState
 */
trait SamTaxCountryStateExistenceCheckerCreateTrait
{
    protected ?SamTaxCountryStateExistenceChecker $samTaxCountryStateExistenceChecker = null;

    /**
     * @return SamTaxCountryStateExistenceChecker
     */
    protected function createSamTaxCountryStateExistenceChecker(): SamTaxCountryStateExistenceChecker
    {
        return $this->samTaxCountryStateExistenceChecker ?: SamTaxCountryStateExistenceChecker::new();
    }

    /**
     * @param SamTaxCountryStateExistenceChecker $samTaxCountryStateExistenceChecker
     * @return $this
     * @internal
     */
    public function setSamTaxCountryStateExistenceChecker(
        SamTaxCountryStateExistenceChecker $samTaxCountryStateExistenceChecker
    ): static {
        $this->samTaxCountryStateExistenceChecker = $samTaxCountryStateExistenceChecker;
        return $this;
    }
}
