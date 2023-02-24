<?php
/**
 * SAM-9413: Make possible for portal admin to create bidder and consignor users linked with main account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Access\Role;

/**
 * Trait UserRoleAccessCheckerCreateTrait
 * @package Sam\User\Privilege\Access
 */
trait UserRoleAccessCheckerCreateTrait
{
    protected ?UserRoleAccessChecker $userRoleAccessChecker = null;

    /**
     * @return UserRoleAccessChecker
     */
    protected function createUserRoleAccessChecker(): UserRoleAccessChecker
    {
        return $this->userRoleAccessChecker ?: UserRoleAccessChecker::new();
    }

    /**
     * @param UserRoleAccessChecker $userRoleAccessChecker
     * @return static
     * @internal
     */
    public function setUserRoleAccessChecker(UserRoleAccessChecker $userRoleAccessChecker): static
    {
        $this->userRoleAccessChecker = $userRoleAccessChecker;
        return $this;
    }
}
