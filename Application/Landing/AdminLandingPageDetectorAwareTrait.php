<?php
/**
 * SAM-4446: Apply Admin Landing Page Detector
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Landing;

/**
 * Trait AdminLandingPageDetectorAwareTrait
 * @package Sam\Application\Landing
 */
trait AdminLandingPageDetectorAwareTrait
{
    protected ?AdminLandingPageDetector $adminLandingPageDetector = null;

    /**
     * @return AdminLandingPageDetector
     */
    protected function getAdminLandingPageDetector(): AdminLandingPageDetector
    {
        if ($this->adminLandingPageDetector === null) {
            $this->adminLandingPageDetector = AdminLandingPageDetector::new();
        }
        return $this->adminLandingPageDetector;
    }

    /**
     * @param AdminLandingPageDetector $adminLandingPageDetector
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAdminLandingPageDetector(AdminLandingPageDetector $adminLandingPageDetector): static
    {
        $this->adminLandingPageDetector = $adminLandingPageDetector;
        return $this;
    }
}
