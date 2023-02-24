<?php
/**
 * SAM-3566: Refactoring for user authorization logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/20/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\User\Auth;


/**
 * Trait AdminAccessCheckerAwareTrait
 * @package
 */
trait AdminAccessCheckerAwareTrait
{
    protected ?AdminAccessChecker $adminAccessChecker = null;

    /**
     * @return AdminAccessChecker
     */
    protected function getAdminAccessChecker(): AdminAccessChecker
    {
        if ($this->adminAccessChecker === null) {
            $this->adminAccessChecker = AdminAccessChecker::new();
        }
        return $this->adminAccessChecker;
    }

    /**
     * @param AdminAccessChecker $adminAccessChecker
     * @return static
     * @internal
     */
    public function setAdminAccessChecker(AdminAccessChecker $adminAccessChecker): static
    {
        $this->adminAccessChecker = $adminAccessChecker;
        return $this;
    }
}
