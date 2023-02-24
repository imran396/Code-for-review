<?php
/**
 * SAM-4498: User billing and cc info validator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           10/15/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Validate;

/**
 * Trait UserBillingCheckerAwareTrait
 * @package Sam\User\Validate
 */
trait UserBillingCheckerAwareTrait
{
    protected ?UserBillingChecker $userBillingChecker = null;

    /**
     * @return UserBillingChecker
     */
    protected function getUserBillingChecker(): UserBillingChecker
    {
        if ($this->userBillingChecker === null) {
            $this->userBillingChecker = UserBillingChecker::new();
        }
        return $this->userBillingChecker;
    }

    /**
     * @param UserBillingChecker $userBillingChecker
     * @return static
     * @internal
     */
    public function setUserBillingChecker(UserBillingChecker $userBillingChecker): static
    {
        $this->userBillingChecker = $userBillingChecker;
        return $this;
    }
}
