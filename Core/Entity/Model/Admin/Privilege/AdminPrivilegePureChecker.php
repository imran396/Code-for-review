<?php
/**
 * Pure logic for checking availability of admin role privileges and sub-privileges.
 * They follow the rule of hierarchical options defined in spec: "20201110-hierarchical-options.md"
 *
 * SAM-7979: Enrich user privilege entities (Admin, Bidder, Consignor)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Admin\Privilege;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AdminPrivilegePureChecker
 * @package Sam\Core\Entity\Model\Admin\Privilege
 */
class AdminPrivilegePureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Check higher layer privileges ---

    public function hasPrivilegeForManageAuctions(?int $adminPrivileges): bool
    {
        return $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::MANAGE_AUCTIONS);
    }

    public function hasPrivilegeForManageInventory(?int $adminPrivileges): bool
    {
        return $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::MANAGE_INVENTORY);
    }

    public function hasPrivilegeForManageUsers(?int $adminPrivileges): bool
    {
        return $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::MANAGE_USERS);
    }

    public function hasPrivilegeForManageInvoices(?int $adminPrivileges): bool
    {
        return $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::MANAGE_INVOICES);
    }

    public function hasPrivilegeForManageSettlements(?int $adminPrivileges): bool
    {
        return $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::MANAGE_SETTLEMENTS);
    }

    public function hasPrivilegeForManageSettings(?int $adminPrivileges): bool
    {
        return $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::MANAGE_SETTINGS);
    }

    public function hasPrivilegeForManageCcInfo(?int $adminPrivileges): bool
    {
        return $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::MANAGE_CC_INFO);
    }

    public function hasPrivilegeForSalesStaff(?int $adminPrivileges): bool
    {
        return $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::SALES_STAFF);
    }

    public function hasPrivilegeForManageReports(?int $adminPrivileges): bool
    {
        return $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::MANAGE_REPORTS);
    }

    /**
     * Cross-domain (superadmin) privilege should be available for admins with direct account relation to the main domain only.
     * Admins related to portal domain accounts can be only regular portal admins without cross-domain access rights (SAM-9666).
     * There is no cross-domain privilege in the Single-tenant installation.
     * @param int|null $adminPrivileges
     * @param int|null $userDirectAccountId
     * @param int $mainAccountId
     * @param bool $isMultipleTenant
     * @return bool
     */
    public function hasPrivilegeForCrossDomain(
        ?int $adminPrivileges,
        ?int $userDirectAccountId,
        int $mainAccountId,
        bool $isMultipleTenant
    ): bool {
        return
            $isMultipleTenant
            && $userDirectAccountId
            && $mainAccountId
            && $userDirectAccountId === $mainAccountId
            && $this->checkAdminPrivilege($adminPrivileges, Constants\AdminPrivilege::SUPERADMIN);
    }

    // --- Check sub-privileges of "ManageAuctions" privilege ---

    public function hasSubPrivilegeForManageAllAuctions(bool $manageAllAuctions, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $manageAllAuctions;
    }

    public function hasSubPrivilegeForDeleteAuction(bool $deleteAuction, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $deleteAuction;
    }

    public function hasSubPrivilegeForArchiveAuction(bool $archiveAuction, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $archiveAuction;
    }

    public function hasSubPrivilegeForResetAuction(bool $resetAuction, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $resetAuction;
    }

    public function hasSubPrivilegeForInformation(bool $information, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $information;
    }

    public function hasSubPrivilegeForPublish(bool $publish, bool $information, ?int $adminPrivileges): bool
    {
        return $this->hasSubPrivilegeForInformation($information, $adminPrivileges)
            && $publish;
    }

    public function hasSubPrivilegeForLots(bool $lots, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $lots;
    }

    public function hasSubPrivilegeForAvailableLots(bool $availableLots, bool $lots, ?int $adminPrivileges): bool
    {
        return $this->hasSubPrivilegeForLots($lots, $adminPrivileges)
            && $availableLots;
    }

    public function hasSubPrivilegeForBidders(bool $bidders, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $bidders;
    }

    public function hasSubPrivilegeForRemainingUsers(bool $remainingUsers, bool $bidders, ?int $adminPrivileges): bool
    {
        return $this->hasSubPrivilegeForBidders($bidders, $adminPrivileges)
            && $remainingUsers;
    }

    public function hasSubPrivilegeForRunLiveAuction(bool $runLiveAuction, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $runLiveAuction;
    }

    public function hasSubPrivilegeForAuctioneerScreen(bool $auctioneerScreen, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $auctioneerScreen;
    }

    public function hasSubPrivilegeForProjector(bool $projector, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $projector;
    }

    public function hasSubPrivilegeForBidIncrements(bool $bidIncrements, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $bidIncrements;
    }

    public function hasSubPrivilegeForBuyersPremium(bool $buyersPremium, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $buyersPremium;
    }

    public function hasSubPrivilegeForPermissions(bool $permissions, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $permissions;
    }

    public function hasSubPrivilegeForCreateBidder(bool $createBidder, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageAuctions($adminPrivileges)
            && $createBidder;
    }

    // --- Check sub-privileges of "ManageUsers" privilege ---

    public function hasSubPrivilegeForUserPasswords(bool $userPasswords, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageUsers($adminPrivileges)
            && $userPasswords;
    }

    public function hasSubPrivilegeForBulkUserExport(bool $bulkUserExport, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageUsers($adminPrivileges)
            && $bulkUserExport;
    }

    public function hasSubPrivilegeForUserPrivileges(bool $userPrivileges, ?int $adminPrivileges): bool
    {
        return $this->hasPrivilegeForManageUsers($adminPrivileges)
            && $userPrivileges;
    }

    public function hasSubPrivilegeForDeleteUser(bool $deleteUser, bool $userPrivileges, ?int $adminPrivileges): bool
    {
        return $this->hasSubPrivilegeForUserPrivileges($userPrivileges, $adminPrivileges)
            && $deleteUser;
    }

    /**
     * TODO: remove when privileges will be individual tinyint(1) fields
     * @param int|null $adminPrivileges
     * @param int $checkPrivilege
     * @return bool
     */
    protected function checkAdminPrivilege(?int $adminPrivileges, int $checkPrivilege): bool
    {
        return (bool)((int)$adminPrivileges & $checkPrivilege);
    }
}
