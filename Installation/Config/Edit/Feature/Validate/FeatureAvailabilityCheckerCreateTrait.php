<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/31/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Feature\Validate;

/**
 * Trait AvailabilityCheckerCreateTrait
 * @package Sam\Installation\Config
 */
trait FeatureAvailabilityCheckerCreateTrait
{
    /**
     * @var FeatureAvailabilityChecker|null
     */
    protected ?FeatureAvailabilityChecker $featureAvailabilityChecker = null;

    /**
     * @return FeatureAvailabilityChecker
     */
    protected function createFeatureAvailabilityChecker(): FeatureAvailabilityChecker
    {
        $checker = $this->featureAvailabilityChecker ?: FeatureAvailabilityChecker::new();
        return $checker;
    }

    /**
     * @param FeatureAvailabilityChecker $checker
     * @return static
     * @internal
     */
    public function setFeatureAvailabilityChecker(FeatureAvailabilityChecker $checker): static
    {
        $this->featureAvailabilityChecker = $checker;
        return $this;
    }
}
