<?php
/**
 * SAM-5624: Separate PrivilegeCheckersAwareTrait to traits per role
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Validate;

/**
 * Trait RoleCheckerAwareTrait
 * @package Sam\User\Privilege\Validate
 */
trait RoleCheckerAwareTrait
{
    protected ?RoleChecker $roleChecker = null;

    /**
     * @return RoleChecker
     */
    protected function getRoleChecker(): RoleChecker
    {
        if ($this->roleChecker === null) {
            $this->roleChecker = RoleChecker::new();
        }
        return $this->roleChecker;
    }

    /**
     * @param RoleChecker $roleChecker
     * @return static
     * @internal
     */
    public function setRoleChecker(RoleChecker $roleChecker): static
    {
        $this->roleChecker = $roleChecker;
        return $this;
    }
}
