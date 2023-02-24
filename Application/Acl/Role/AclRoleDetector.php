<?php
/**
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/20/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Acl\Role;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuthIdentityManager
 */
class AclRoleDetector extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @param Ui $ui
     * @return string
     */
    public function detect(?int $editorUserId, Ui $ui): string
    {
        $role = Constants\Role::ACL_ANONYMOUS;
        if (!$editorUserId) {
            return $role;
        }
        if ($ui->isWebAdmin()) {
            $admin = $this->getUserLoader()->loadAdmin($editorUserId);
            if ($admin) {
                $role = $admin->AdminPrivileges === Constants\AdminPrivilege::SUM
                    ? Constants\Role::ACL_ADMIN
                    : Constants\Role::ACL_STAFF;
            }
        } elseif ($ui->isWebResponsive()) {
            $role = Constants\Role::ACL_CUSTOMER;
        }
        return $role;
    }
}
