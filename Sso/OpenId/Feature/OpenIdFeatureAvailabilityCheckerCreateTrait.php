<?php
/**
 * SAM-10584: SAM SSO
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\OpenId\Feature;

trait OpenIdFeatureAvailabilityCheckerCreateTrait
{
    protected ?OpenIdFeatureAvailabilityChecker $openIdFeatureAvailabilityChecker = null;

    /**
     * @return OpenIdFeatureAvailabilityChecker
     */
    protected function createOpenIdFeatureAvailabilityChecker(): OpenIdFeatureAvailabilityChecker
    {
        return $this->openIdFeatureAvailabilityChecker ?: OpenIdFeatureAvailabilityChecker::new();
    }

    /**
     * @param OpenIdFeatureAvailabilityChecker $openIdFeatureAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setOpenIdFeatureAvailabilityChecker(
        OpenIdFeatureAvailabilityChecker $openIdFeatureAvailabilityChecker
    ): static {
        $this->openIdFeatureAvailabilityChecker = $openIdFeatureAvailabilityChecker;
        return $this;
    }
}
