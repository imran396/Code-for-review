<?php
/**
 * SAM-9383: Sign Up: "Simplified signup page" condition uncheck, error occurs
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Profile\Field;

/**
 * Trait UserProfileFieldAvailabilityCheckerCreateTrait
 * @package Sam\User\Profile\Field
 */
trait UserProfileFieldAvailabilityCheckerCreateTrait
{
    protected ?UserProfileFieldAvailabilityChecker $userProfileFieldAvailabilityChecker = null;

    /**
     * @return UserProfileFieldAvailabilityChecker
     */
    protected function createUserProfileFieldAvailabilityChecker(): UserProfileFieldAvailabilityChecker
    {
        return $this->userProfileFieldAvailabilityChecker ?: UserProfileFieldAvailabilityChecker::new();
    }

    /**
     * @param UserProfileFieldAvailabilityChecker $userProfileFieldAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setUserProfileFieldAvailabilityChecker(UserProfileFieldAvailabilityChecker $userProfileFieldAvailabilityChecker): static
    {
        $this->userProfileFieldAvailabilityChecker = $userProfileFieldAvailabilityChecker;
        return $this;
    }
}
