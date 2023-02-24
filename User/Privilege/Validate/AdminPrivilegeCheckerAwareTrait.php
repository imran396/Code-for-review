<?php
/**
 * SAM-5624: Separate PrivilegeCheckersAwareTrait to traits per role
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 Aug, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Validate;

/**
 * Trait AdminPrivilegeCheckerAwareTrait
 * @package Sam\User\Privilege\Validate
 */
trait AdminPrivilegeCheckerAwareTrait
{
    protected ?AdminPrivilegeChecker $adminPrivilegeChecker = null;

    /**
     * @param AdminPrivilegeChecker $adminPrivilegeChecker
     * @return static
     * @internal
     */
    public function setAdminPrivilegeChecker(AdminPrivilegeChecker $adminPrivilegeChecker): static
    {
        $this->adminPrivilegeChecker = $adminPrivilegeChecker;
        return $this;
    }

    /**
     * @return AdminPrivilegeChecker
     */
    protected function getAdminPrivilegeChecker(): AdminPrivilegeChecker
    {
        if ($this->adminPrivilegeChecker === null) {
            $this->adminPrivilegeChecker = AdminPrivilegeChecker::new();
        }
        return $this->adminPrivilegeChecker;
    }
}
