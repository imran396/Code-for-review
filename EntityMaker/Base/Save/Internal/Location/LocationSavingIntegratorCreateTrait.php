<?php
/**
 * SAM-10360: Decouple location validation and saving logic from parent classes
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Save\Internal\Location;

/**
 * Trait LocationSavingIntegratorCreateTrait
 * @package Sam\EntityMaker\Base\Save\Internal\Location
 */
trait LocationSavingIntegratorCreateTrait
{
    /**
     * @var LocationSavingIntegrator|null
     */
    protected ?LocationSavingIntegrator $locationSavingIntegrator = null;

    /**
     * @return LocationSavingIntegrator
     */
    protected function createLocationSavingIntegrator(): LocationSavingIntegrator
    {
        return $this->locationSavingIntegrator ?: LocationSavingIntegrator::new();
    }

    /**
     * @param LocationSavingIntegrator $locationSavingIntegrator
     * @return $this
     * @internal
     */
    public function setLocationSavingIntegrator(LocationSavingIntegrator $locationSavingIntegrator): static
    {
        $this->locationSavingIntegrator = $locationSavingIntegrator;
        return $this;
    }
}
