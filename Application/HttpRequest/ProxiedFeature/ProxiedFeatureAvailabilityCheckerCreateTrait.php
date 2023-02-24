<?php
/**
 * SAM-10584: SAM SSO
 *
 * Project        SAM
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Sep 29, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Application\HttpRequest\ProxiedFeature;

trait ProxiedFeatureAvailabilityCheckerCreateTrait
{
    protected ?ProxiedFeatureAvailabilityChecker $proxiedFeatureAvailabilityChecker = null;

    /**
     * @return ProxiedFeatureAvailabilityChecker
     */
    protected function createProxiedFeatureAvailabilityChecker(): ProxiedFeatureAvailabilityChecker
    {
        return $this->proxiedFeatureAvailabilityChecker ?: ProxiedFeatureAvailabilityChecker::new();
    }

    /**
     * @param ProxiedFeatureAvailabilityChecker $proxiedFeatureAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setProxiedFeatureAvailabilityChecker(
        ProxiedFeatureAvailabilityChecker $proxiedFeatureAvailabilityChecker
    ): static {
        $this->proxiedFeatureAvailabilityChecker = $proxiedFeatureAvailabilityChecker;
        return $this;
    }
}
