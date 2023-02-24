<?php
/**
 * Admin privileges checking service.
 *
 * Init service before privilege check using one of these methods:
 * initByAdmin(\Admin) - set admin info record
 * initByUser(\User) - admin info will be loaded by user id
 * initByUserId(int) - '' - '' -
 *
 * Related tickets:
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

namespace Sam\User\Privilege\Validate;

use Admin;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class AdminPrivilegeChecker
 * @package Sam\User\Privilege\Validate
 */
class AdminPrivilegeChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    protected ?Admin $admin = null;
    protected ?User $user = null;
    /**
     * Get data from read-only db, if read-only db is available
     */
    protected bool $isReadOnlyDb = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init service with \Admin record, that stores privileges
     * @param Admin|null $admin null - for case, when user isn't admin
     * @return static
     */
    public function initByAdmin(?Admin $admin): static
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * Alternative way to init checker. Admin info will be loaded by passed user id.
     * @param int|null $userId null - for anonymous user
     * @return static
     */
    public function initByUserId(?int $userId = null): static
    {
        $admin = $userId
            ? $this->getUserLoader()->loadAdmin($userId, $this->isReadOnlyDb)
            : null;
        $this->initByAdmin($admin);
        return $this;
    }

    /**
     * Alternative way to init checker. Admin info will be loaded by passed user.
     * @param User|null $user null - for anonymous user
     * @return static
     */
    public function initByUser(?User $user): static
    {
        $this->user = $user;
        $this->initByUserId($user->Id ?? null);
        return $this;
    }

    /**
     * Use read-only db, if it is available
     * @param bool $enable
     * @return static
     */
    public function enableReadOnlyDb(bool $enable): static
    {
        $this->isReadOnlyDb = $enable;
        return $this;
    }

    /**
     * Has admin role
     * @return bool
     */
    public function isAdmin(): bool
    {
        $has = $this->getAdmin() instanceof Admin
            && $this->getAdmin()->Id > 0;
        return $has;
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageAuctions(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasPrivilegeForManageAuctions();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageInventory(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasPrivilegeForManageInventory();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageUsers(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasPrivilegeForManageUsers();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageInvoices(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasPrivilegeForManageInvoices();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageSettlements(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasPrivilegeForManageSettlements();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageSettings(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasPrivilegeForManageSettings();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageCcInfo(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasPrivilegeForManageCcInfo();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForSalesStaff(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasPrivilegeForSalesStaff();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageReports(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasPrivilegeForManageReports();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForSuperadmin(): bool
    {
        $admin = $this->getAdmin();
        if (!$admin) {
            return false;
        }

        $userDirectAccountId = $this->getUser()->AccountId ?? null;
        $mainAccountId = $this->cfg()->get('core->portal->mainAccountId');
        $isMultipleTenant = (bool)$this->cfg()->get('core->portal->enabled');
        return $admin->hasPrivilegeForCrossDomain($userDirectAccountId, $mainAccountId, $isMultipleTenant);
    }

    // Sub-privileges

    /**
     * @return bool
     */
    public function hasSubPrivilegeForManageAllAuctions(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForManageAllAuctions();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForDeleteAuction(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForDeleteAuction();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForArchiveAuction(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForArchiveAuction();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForResetAuction(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForResetAuction();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForInformation(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForInformation();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForPublish(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForPublish();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForLots(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForLots();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForAvailableLots(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForAvailableLots();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForBidders(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForBidders();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForRemainingUsers(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForRemainingUsers();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForRunLiveAuction(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForRunLiveAuction();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForAuctioneerScreen(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForAuctioneerScreen();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForProjector(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForProjector();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForBidIncrements(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForBidIncrements();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForBuyersPremium(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForBuyersPremium();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForPermissions(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForPermissions();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForCreateBidder(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForCreateBidder();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForUserPasswords(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForUserPasswords();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForBulkUserExport(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForBulkUserExport();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForUserPrivileges(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForUserPrivileges();
    }

    /**
     * @return bool
     */
    public function hasSubPrivilegeForDeleteUser(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->hasSubPrivilegeForDeleteUser();
    }

    /**
     * @param int $privilege
     * @return bool
     */
    public function checkAdminPrivilege(int $privilege): bool
    {
        $admin = $this->getAdmin();
        $has = $this->isAdmin()
            && $admin
            && $admin->AdminPrivileges & $privilege;
        return $has;
    }

    /**
     * Check, if admin has at least one sub-privilege for ManageAuctions block, except ManageAllAuctions
     * @return bool
     */
    public function hasAnyPrivilegeForManageAuctions(): bool
    {
        $admin = $this->getAdmin();
        if (!$admin) {
            return false;
        }
        $permRestrictionCount = 0;
        $subPrivileges = Constants\AdminPrivilege::$manageAuctionSubPrivileges;
        foreach ($subPrivileges as $subPrivilege) {
            if ($subPrivilege === Constants\AdminPrivilege::SUB_AUCTION_MANAGE_ALL) {
                continue;
            }
            if (!$admin->$subPrivilege) {
                $permRestrictionCount++;
            }
        }

        $has = ($permRestrictionCount !== count($subPrivileges) - 1);
        return $has;
    }

    /**
     * @return Admin|null
     */
    protected function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    /**
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if ($this->user === null) {
            $userId = $this->getAdmin()->UserId ?? null;
            $this->user = $this->getUserLoader()->load($userId, $this->isReadOnlyDb);
            if (!$this->user) {
                log_error("Available user not found by id" . composeSuffix(['u' => $userId]));
                return null;
            }
        }
        return $this->user;
    }
}
