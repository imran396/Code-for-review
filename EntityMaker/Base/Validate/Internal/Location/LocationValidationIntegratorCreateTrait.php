<?php
/**
 * SAM-10360: Decouple location validation and validation logic from parent classes
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

namespace Sam\EntityMaker\Base\Validate\Internal\Location;

/**
 * Trait LocationValidationIntegratorCreateTrait
 * @package Sam\EntityMaker\Base\Validate\Internal\Location
 */
trait LocationValidationIntegratorCreateTrait
{
    /**
     * @var LocationValidationIntegrator|null
     */
    protected ?LocationValidationIntegrator $locationValidationIntegrator = null;

    /**
     * @return LocationValidationIntegrator
     */
    protected function createLocationValidationIntegrator(): LocationValidationIntegrator
    {
        return $this->locationValidationIntegrator ?: LocationValidationIntegrator::new();
    }

    /**
     * @param LocationValidationIntegrator $locationValidationIntegrator
     * @return $this
     * @internal
     */
    public function setLocationValidationIntegrator(LocationValidationIntegrator $locationValidationIntegrator): static
    {
        $this->locationValidationIntegrator = $locationValidationIntegrator;
        return $this;
    }
}
