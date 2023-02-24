<?php
/**
 *
 * SAM-4738: UserAccount management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-27
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Account\Validate;

/**
 * Trait UserAccountExistenceCheckerAwareTrait
 * @package Sam\User\Account\Validate
 */
trait UserAccountExistenceCheckerAwareTrait
{
    protected ?UserAccountExistenceChecker $userAccountExistenceChecker = null;

    /**
     * @return UserAccountExistenceChecker
     */
    protected function getUserAccountExistenceChecker(): UserAccountExistenceChecker
    {
        if ($this->userAccountExistenceChecker === null) {
            $this->userAccountExistenceChecker = UserAccountExistenceChecker::new();
        }
        return $this->userAccountExistenceChecker;
    }

    /**
     * @param UserAccountExistenceChecker $userAccountExistenceChecker
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setUserAccountExistenceChecker(UserAccountExistenceChecker $userAccountExistenceChecker): static
    {
        $this->userAccountExistenceChecker = $userAccountExistenceChecker;
        return $this;
    }
}
