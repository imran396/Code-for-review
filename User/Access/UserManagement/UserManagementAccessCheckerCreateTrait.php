<?php
/**
 * SAM-8049: Admin User Edit - restricted users should not be accessible by direct link
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access\UserManagement;

/**
 * Trait UserManagementAccessCheckerCreateTrait
 * @package Sam\
 */
trait UserManagementAccessCheckerCreateTrait
{
    protected ?UserManagementAccessChecker $userManagementAccessChecker = null;

    /**
     * @return UserManagementAccessChecker
     */
    protected function createUserManagementAccessChecker(): UserManagementAccessChecker
    {
        return $this->userManagementAccessChecker ?: UserManagementAccessChecker::new();
    }

    /**
     * @param UserManagementAccessChecker $userManagementAccessChecker
     * @return $this
     * @internal
     */
    public function setUserManagementAccessChecker(UserManagementAccessChecker $userManagementAccessChecker): static
    {
        $this->userManagementAccessChecker = $userManagementAccessChecker;
        return $this;
    }
}
