<?php
/**
 * SAM-6923: Login to bid, completing signup is not redirecting into the auction registration process
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Landing\Responsive;

/**
 * Trait ResponsiveLandingPageDetectorCreateTrait
 * @package Sam\Application\Landing\Responsive
 */
trait ResponsiveLandingPageDetectorCreateTrait
{
    protected ?ResponsiveLandingPageDetector $responsiveLandingPageDetector = null;

    /**
     * @return ResponsiveLandingPageDetector
     */
    protected function createResponsiveLandingPageDetector(): ResponsiveLandingPageDetector
    {
        return $this->responsiveLandingPageDetector ?: ResponsiveLandingPageDetector::new();
    }

    /**
     * @param ResponsiveLandingPageDetector $responsiveLandingPageDetector
     * @return $this
     * @internal
     */
    public function setResponsiveLandingPageDetector(ResponsiveLandingPageDetector $responsiveLandingPageDetector): static
    {
        $this->responsiveLandingPageDetector = $responsiveLandingPageDetector;
        return $this;
    }
}
