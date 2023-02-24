<?php
/**
 * Trait for UserDataIntegrityChecker
 *
 * SAM-5071: Data integrity checker - there shall only be one active user_cust_data record for one user
 * and one user_cust_field
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/11/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Validate;

/**
 * Trait UserDataIntegrityCheckerAwareTrait
 * @package Sam\User\Validate
 */
trait UserDataIntegrityCheckerAwareTrait
{
    protected ?UserDataIntegrityChecker $userDataIntegrityChecker = null;

    /**
     * @return UserDataIntegrityChecker
     */
    protected function getUserDataIntegrityChecker(): UserDataIntegrityChecker
    {
        if ($this->userDataIntegrityChecker === null) {
            $this->userDataIntegrityChecker = UserDataIntegrityChecker::new();
        }
        return $this->userDataIntegrityChecker;
    }

    /**
     * @param UserDataIntegrityChecker $userDataIntegrityChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserDataIntegrityChecker(UserDataIntegrityChecker $userDataIntegrityChecker): static
    {
        $this->userDataIntegrityChecker = $userDataIntegrityChecker;
        return $this;
    }
}
