<?php
/**
 * SAM-7957: Unit tests for "new account on signup" feature
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\NewAccount\Feature;

/**
 * Trait SignupNewAccountFeatureAvailabilityCheckerCreateTrait
 * @package Sam\User\Signup\NewAccount\Feature
 */
trait NewAccountOnSignupFeatureAvailabilityCheckerCreateTrait
{
    protected ?NewAccountOnSignupFeatureAvailabilityChecker $newAccountOnSignupFeatureAvailabilityChecker = null;

    /**
     * @return NewAccountOnSignupFeatureAvailabilityChecker
     */
    protected function createNewAccountOnSignupFeatureAvailabilityChecker(): NewAccountOnSignupFeatureAvailabilityChecker
    {
        return $this->newAccountOnSignupFeatureAvailabilityChecker ?: NewAccountOnSignupFeatureAvailabilityChecker::new();
    }

    /**
     * @param NewAccountOnSignupFeatureAvailabilityChecker $checker
     * @return $this
     * @internal
     */
    public function setNewAccountOnSignupFeatureAvailabilityChecker(NewAccountOnSignupFeatureAvailabilityChecker $checker): static
    {
        $this->newAccountOnSignupFeatureAvailabilityChecker = $checker;
        return $this;
    }
}
