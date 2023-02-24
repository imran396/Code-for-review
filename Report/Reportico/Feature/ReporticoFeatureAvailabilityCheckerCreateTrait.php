<?php
/**
 * SAM-9547: Add default reportico reports tab to SAM reports page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Reportico\Feature;

/**
 * Trait ReporticoFeatureAvailabilityCheckerCreateTrait
 * @package Sam\Report\Reportico\Feature
 */
trait ReporticoFeatureAvailabilityCheckerCreateTrait
{
    protected ?ReporticoFeatureAvailabilityChecker $reporticoFeatureAvailabilityChecker = null;

    /**
     * @return ReporticoFeatureAvailabilityChecker
     */
    protected function createReporticoFeatureAvailabilityChecker(): ReporticoFeatureAvailabilityChecker
    {
        return $this->reporticoFeatureAvailabilityChecker ?: ReporticoFeatureAvailabilityChecker::new();
    }

    /**
     * @param ReporticoFeatureAvailabilityChecker $reporticoFeatureAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setReporticoFeatureAvailabilityChecker(ReporticoFeatureAvailabilityChecker $reporticoFeatureAvailabilityChecker): static
    {
        $this->reporticoFeatureAvailabilityChecker = $reporticoFeatureAvailabilityChecker;
        return $this;
    }
}
