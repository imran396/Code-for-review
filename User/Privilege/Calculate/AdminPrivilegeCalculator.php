<?php
/**
 * Helper functions for admin privileges
 *
 * SAM-3560: Privilege checker class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           27 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Calculate;

use Admin;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AdminHelper
 * @package Sam\User\Privilege\Validate
 */
class AdminPrivilegeCalculator extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add privilege to Admin->AdminPrivileges
     * @param Admin $admin
     * @param int $privilege
     * @return Admin
     */
    public function add(Admin $admin, int $privilege): Admin
    {
        if (!($admin->AdminPrivileges & $privilege)) {
            $admin->AdminPrivileges += $privilege;
        }
        return $admin;
    }

    /**
     * Remove privilege from Admin->AdminPrivileges
     * @param Admin $admin
     * @param int $privilege
     * @return Admin
     */
    public function remove(Admin $admin, int $privilege): Admin
    {
        if ($admin->AdminPrivileges & $privilege) {
            $admin->AdminPrivileges -= $privilege;
        }
        return $admin;
    }
}
