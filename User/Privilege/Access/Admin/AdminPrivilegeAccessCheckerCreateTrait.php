<?php
/**
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Access\Admin;

/**
 * Trait AdminPrivilegeAccessCheckerCreateTrait
 * @package Sam\User\Privilege\Access
 */
trait AdminPrivilegeAccessCheckerCreateTrait
{
    protected ?AdminPrivilegeAccessChecker $adminPrivilegeAccessChecker = null;

    /**
     * @return AdminPrivilegeAccessChecker
     */
    protected function createAdminPrivilegeAccessChecker(): AdminPrivilegeAccessChecker
    {
        return $this->adminPrivilegeAccessChecker ?: AdminPrivilegeAccessChecker::new();
    }

    /**
     * @param AdminPrivilegeAccessChecker $adminPrivilegeAccessChecker
     * @return static
     * @internal
     */
    public function setAdminPrivilegeAccessChecker(AdminPrivilegeAccessChecker $adminPrivilegeAccessChecker): static
    {
        $this->adminPrivilegeAccessChecker = $adminPrivilegeAccessChecker;
        return $this;
    }
}
