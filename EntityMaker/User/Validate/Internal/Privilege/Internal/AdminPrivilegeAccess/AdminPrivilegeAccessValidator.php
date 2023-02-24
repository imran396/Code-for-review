<?php
/**
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess\Internal\Load\DataProviderCreateTrait;
use Sam\EntityMaker\User\Validate\Internal\Privilege\PrivilegeValidationInput as Input;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess\AdminPrivilegeAccessValidationResult as Result;
use Sam\User\Privilege\Access\Admin\AdminPrivilegeAccessCheckerCreateTrait;

/**
 * Class AdminPrivilegeAccessValidator
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess
 */
class AdminPrivilegeAccessValidator extends CustomizableClass
{
    use AdminPrivilegeAccessCheckerCreateTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks if the editor has the right to set admin privileges to user.
     * Checks only those privileges that have changed.
     *
     * @param Input $input
     * @return AdminPrivilegeAccessValidationResult
     */
    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        $result = $this->checkCanEditAdminPrivileges($input, $result);
        $result = $this->checkCanEditManageAuctionSubPrivileges($input, $result);
        $result = $this->checkCanEditManageUserSubPrivileges($input, $result);
        return $result;
    }

    protected function checkCanEditAdminPrivileges(Input $input, Result $result): Result
    {
        $privilegeAccessChecker = $this->createAdminPrivilegeAccessChecker();
        if (
            $this->isPrivilegeChanged($input->id, $input->manageAuctions, Constants\AdminPrivilege::MANAGE_AUCTIONS)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::MANAGE_AUCTIONS)
        ) {
            $result->addError(Result::ERR_MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isPrivilegeChanged($input->id, $input->manageInventory, Constants\AdminPrivilege::MANAGE_INVENTORY)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::MANAGE_INVENTORY)
        ) {
            $result->addError(Result::ERR_MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isPrivilegeChanged($input->id, $input->manageUsers, Constants\AdminPrivilege::MANAGE_USERS)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::MANAGE_USERS)
        ) {
            $result->addError(Result::ERR_MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isPrivilegeChanged($input->id, $input->manageInvoices, Constants\AdminPrivilege::MANAGE_INVOICES)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::MANAGE_INVOICES)
        ) {
            $result->addError(Result::ERR_MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isPrivilegeChanged($input->id, $input->manageSettlements, Constants\AdminPrivilege::MANAGE_SETTLEMENTS)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::MANAGE_SETTLEMENTS)
        ) {
            $result->addError(Result::ERR_MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isPrivilegeChanged($input->id, $input->manageSettings, Constants\AdminPrivilege::MANAGE_SETTINGS)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::MANAGE_SETTINGS)
        ) {
            $result->addError(Result::ERR_MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isPrivilegeChanged($input->id, $input->manageCcInfo, Constants\AdminPrivilege::MANAGE_CC_INFO)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::MANAGE_CC_INFO)
        ) {
            $result->addError(Result::ERR_MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isPrivilegeChanged($input->id, $input->salesStaff, Constants\AdminPrivilege::SALES_STAFF)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::SALES_STAFF)
        ) {
            $result->addError(Result::ERR_SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isPrivilegeChanged($input->id, $input->manageReports, Constants\AdminPrivilege::MANAGE_REPORTS)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::MANAGE_REPORTS)
        ) {
            $result->addError(Result::ERR_MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isPrivilegeChanged($input->id, $input->crossDomain, Constants\AdminPrivilege::SUPERADMIN)
            && !$privilegeAccessChecker->canEditAdminPrivilege($input->editorUserId, Constants\AdminPrivilege::SUPERADMIN)
        ) {
            $result->addError(Result::ERR_SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE);
        }

        return $result;
    }

    protected function checkCanEditManageAuctionSubPrivileges(Input $input, Result $result): Result
    {
        $privilegeAccessChecker = $this->createAdminPrivilegeAccessChecker();
        if (
            $this->isSubPrivilegeChanged($input->id, $input->manageAllAuctions, Constants\AdminPrivilege::SUB_AUCTION_MANAGE_ALL)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_MANAGE_ALL)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->deleteAuction, Constants\AdminPrivilege::SUB_AUCTION_DELETE)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_DELETE)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->archiveAuction, Constants\AdminPrivilege::SUB_AUCTION_ARCHIVE)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_ARCHIVE)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->resetAuction, Constants\AdminPrivilege::SUB_AUCTION_RESET)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_RESET)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->information, Constants\AdminPrivilege::SUB_AUCTION_INFORMATION)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_INFORMATION)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->publish, Constants\AdminPrivilege::SUB_AUCTION_PUBLISH)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_PUBLISH)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->lots, Constants\AdminPrivilege::SUB_AUCTION_LOT_LIST)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_LOT_LIST)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->availableLots, Constants\AdminPrivilege::SUB_AUCTION_AVAILABLE_LOT)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_AVAILABLE_LOT)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->bidders, Constants\AdminPrivilege::SUB_AUCTION_BIDDER)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_BIDDER)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->remainingUsers, Constants\AdminPrivilege::SUB_AUCTION_REMAINING_USER)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_REMAINING_USER)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->runLiveAuction, Constants\AdminPrivilege::SUB_AUCTION_RUN_LIVE)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_RUN_LIVE)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->auctioneerScreen, Constants\AdminPrivilege::SUB_AUCTION_AUCTIONEER)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_AUCTIONEER)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->projector, Constants\AdminPrivilege::SUB_AUCTION_PROJECTOR)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_PROJECTOR)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE);
        }


        if (
            $this->isSubPrivilegeChanged($input->id, $input->bidIncrements, Constants\AdminPrivilege::SUB_AUCTION_BID_INCREMENT)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_BID_INCREMENT)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->buyersPremium, Constants\AdminPrivilege::SUB_AUCTION_BUYER_PREMIUM)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_BUYER_PREMIUM)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->permissions, Constants\AdminPrivilege::SUB_AUCTION_PERMISSION)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_PERMISSION)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->createBidder, Constants\AdminPrivilege::SUB_AUCTION_CREATE_BIDDER)
            && !$privilegeAccessChecker->canEditManageAuctionSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_AUCTION_CREATE_BIDDER)
        ) {
            $result->addError(Result::ERR_SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE);
        }

        return $result;
    }

    protected function checkCanEditManageUserSubPrivileges(Input $input, Result $result): Result
    {
        $privilegeAccessChecker = $this->createAdminPrivilegeAccessChecker();
        if (
            $this->isSubPrivilegeChanged($input->id, $input->bulkUserExport, Constants\AdminPrivilege::SUB_USER_BULK_EXPORT)
            && !$privilegeAccessChecker->canEditManageUserSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_USER_BULK_EXPORT)
        ) {
            $result->addError(Result::ERR_SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->userPasswords, Constants\AdminPrivilege::SUB_USER_PASSWORD)
            && !$privilegeAccessChecker->canEditManageUserSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_USER_PASSWORD)
        ) {
            $result->addError(Result::ERR_SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->userPrivileges, Constants\AdminPrivilege::SUB_USER_PRIVILEGE)
            && !$privilegeAccessChecker->canEditManageUserSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_USER_PRIVILEGE)
        ) {
            $result->addError(Result::ERR_SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE);
        }

        if (
            $this->isSubPrivilegeChanged($input->id, $input->deleteUser, Constants\AdminPrivilege::SUB_USER_DELETE)
            && !$privilegeAccessChecker->canEditManageUserSubPrivilege($input->editorUserId, Constants\AdminPrivilege::SUB_USER_DELETE)
        ) {
            $result->addError(Result::ERR_SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE);
        }

        return $result;
    }

    protected function isPrivilegeChanged(?int $userId, ?string $newPrivilegeValue, int $privilege): bool
    {
        if ($newPrivilegeValue === null) {
            return false;
        }

        $admin = $this->createDataProvider()->loadAdmin($userId);
        $hasPrivilege = $admin
            && ($admin->AdminPrivileges & $privilege);
        $isChanged = ValueResolver::new()->isTrue($newPrivilegeValue) !== $hasPrivilege;
        return $isChanged;
    }

    protected function isSubPrivilegeChanged(?int $userId, ?string $newSubPrivilegeValue, string $subPrivilege): bool
    {
        if ($newSubPrivilegeValue === null) {
            return false;
        }

        $admin = $this->createDataProvider()->loadAdmin($userId);
        $hasSubPrivilege = $admin
            && $admin->{$subPrivilege};
        $isChanged = ValueResolver::new()->isTrue($newSubPrivilegeValue) !== $hasSubPrivilege;
        return $isChanged;
    }
}
