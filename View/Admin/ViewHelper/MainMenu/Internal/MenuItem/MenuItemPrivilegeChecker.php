<?php
/**
 * SAM-7717: Refactor admin menu tabs rendering module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * Class MenuItemPrivilegeChecker
 * @package Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem
 * @internal
 */
class MenuItemPrivilegeChecker extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @return static
     */
    public function construct(int $userId): static
    {
        $this->getAdminPrivilegeChecker()->initByUserId($userId);
        return $this;
    }

    /**
     * @param int $privilege
     * @return bool
     */
    public function hasPrivilege(int $privilege): bool
    {
        if ($privilege === Constants\AdminPrivilege::NONE) {
            return true;
        }

        if ($privilege === Constants\AdminPrivilege::MANAGE_AUCTIONS) {
            $hasPrivilege = $this->hasPrivilegeForManageAuctions();
        } else {
            $hasPrivilege = $this->getAdminPrivilegeChecker()->checkAdminPrivilege($privilege);
        }
        return $hasPrivilege;
    }

    /**
     * @return bool
     */
    protected function hasPrivilegeForManageAuctions(): bool
    {
        $adminPrivilegeChecker = $this->getAdminPrivilegeChecker();
        $hasPrivilege = $adminPrivilegeChecker->hasPrivilegeForManageAuctions()
            && $adminPrivilegeChecker->hasAnyPrivilegeForManageAuctions();
        return $hasPrivilege;
    }
}
