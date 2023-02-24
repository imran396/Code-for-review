<?php
/**
 * This service check access of editor user to target user in context of visiting system account.
 * It doesn't check editor user access to system account according to installation configuration, e.g. when it is single- or multiple- tenant.
 *
 * SAM-8049: Admin User Edit - restricted users should not be accessible by direct link
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access\UserManagement;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Settings\SettingsManager;
use Sam\User\Access\UserManagement\Internal\Load\DataProvider;
use Sam\User\Access\UserManagement\UserManagementAccessCheckResult as Result;

/**
 * Class UserManagementAccessChecker
 * @package Sam\User\Access\ManageUser
 */
class UserManagementAccessChecker extends CustomizableClass
{
    use OptionalsTrait;

    // --- Incoming values ---

    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT; // bool
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int
    public const OP_SHARE_USER_INFO = OptionalKeyConstants::KEY_SHARE_USER_INFO; // int

    // --- Internal ---

    protected ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param int|null $targetUserId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @return Result
     */
    public function check(?int $targetUserId, ?int $editorUserId, int $systemAccountId): Result
    {
        $result = Result::new()->construct($targetUserId, $editorUserId, $systemAccountId);

        $result = $this->checkBasicCases($result);
        if ($result->hasError()) {
            return $result;
        }

        if (!$targetUserId) {
            return $result->addEditViewSuccess(Result::OK_NEW_USER_ALLOWED);
        }

        if ($this->isMultipleTenant()) {
            return $this->checkMultipleTenantInstallation($result);
        }

        return $this->checkSingleTenantInstallation($result);
    }

    /**
     * Perform basic validations.
     * Return false, if any of them has been failed - this means, we shouldn't continue checking.
     * Return true, if all basic validations have succeeded - this means, we should continue checking.
     * @param Result $result
     * @return Result
     */
    protected function checkBasicCases(Result $result): Result
    {
        $targetUserId = $result->targetUserId();
        $editorUserId = $result->editorUserId();
        $dataProvider = $this->getDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);

        if (!$editorUserId) {
            return $result->addError(Result::ERR_ANONYMOUS_EDITOR_USER_CANNOT_ACCESS_ANY_USER);
        }

        if (!$dataProvider->existEditorUser($editorUserId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_EDITOR_USER_ABSENT);
        }

        if (
            $result->targetUserId()
            && !$dataProvider->existTargetUser($targetUserId, $isReadOnlyDb)
        ) {
            return $result->addError(Result::ERR_TARGET_USER_ABSENT);
        }

        /**
         * Check, that editor user is admin with "Manage Users" privilege
         */
        $hasEditorPrivilegeForManagerUsers = $dataProvider->hasEditorPrivilegeForManageUsers($editorUserId, $isReadOnlyDb);
        if (!$hasEditorPrivilegeForManagerUsers) {
            return $result->addError(Result::ERR_EDITOR_USER_WITHOUT_MANAGE_USERS_PRIVILEGE_CANNOT_ACCESS_ANY_USER);
        }

        return $result;
    }

    /**
     * @param Result $result
     * @return Result
     */
    protected function checkMultipleTenantInstallation(Result $result): Result
    {
        $editorUserId = $result->editorUserId();
        $dataProvider = $this->getDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);

        $isEditorCrossDomainAdmin = $dataProvider->isEditorCrossDomainAdmin($editorUserId, $isReadOnlyDb);
        if ($isEditorCrossDomainAdmin) {
            return $this->checkMultipleTenantInstallationForCrossDomainAdmin($result);
        }

        return $this->checkMultipleTenantInstallationForRegularAdmin($result);
    }

    /**
     * @param Result $result
     * @return Result
     */
    protected function checkMultipleTenantInstallationForCrossDomainAdmin(Result $result): Result
    {
        $targetUserId = $result->targetUserId();
        $systemAccountId = $result->systemAccountId();
        $dataProvider = $this->getDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $shareUserInfo = (int)$this->fetchOptional(self::OP_SHARE_USER_INFO);

        $isMainSystemAccount = $this->isMainAccount($systemAccountId);
        if ($isMainSystemAccount) {
            /**
             * Cross-domain admin, who visits main domain,
             * He can Edit and View any user.
             */
            return $result->addEditViewSuccess(
                Result::OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_MAIN_DOMAIN_CAN_ACCESS_ANY_TARGET_USER
            );
        }

        /**
         * Cross-domain admin, who visits portal domain.
         */
        $targetUserDirectAccountId = $dataProvider->loadTargetUserDirectAccountId($targetUserId, $isReadOnlyDb);
        if ($targetUserDirectAccountId === $systemAccountId) {
            /**
             * He can Edit and View any user who is directly assigned to account of the visiting portal domain.
             */
            return $result->addEditViewSuccess(
                Result::OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_DIRECT_ACCOUNT_OF_THIS_PORTAL
            );
        }

        $isAmongTargetUserCollateralAccounts = $dataProvider->isAmongTargetUserCollateralAccounts($systemAccountId, $targetUserId, $isReadOnlyDb);
        if ($isAmongTargetUserCollateralAccounts) {
            /**
             * When visiting account is among target user collateral accounts,
             * and "Share User Info" is "Edit"
             */
            if ($shareUserInfo === Constants\ShareUserInfo::EDIT) {
                return $result->addEditViewSuccess(
                    Result::OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_EDIT
                );
            }

            /**
             * When visiting account is among target user collateral accounts,
             * and "Share User Info" is "View"
             */
            if ($shareUserInfo === Constants\ShareUserInfo::VIEW) {
                return $result->addOnlyViewSuccess(
                    Result::OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_VIEW
                );
            }

            /**
             * When visiting account is among target user collateral accounts,
             * and "Share User Info" is "None"
             */
            if ($shareUserInfo === Constants\ShareUserInfo::NONE) {
                return $result->addError(
                    Result::ERR_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_NONE
                );
            }
        }

        /**
         * When visiting account is not among target user collateral accounts
         */
        return $result->addError(
            Result::ERR_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITHOUT_DIRECT_AND_COLLATERAL_ACCOUNT_OF_THIS_PORTAL
        );
    }

    /**
     * @param Result $result
     * @return Result
     */
    protected function checkMultipleTenantInstallationForRegularAdmin(Result $result): Result
    {
        $editorUserId = $result->editorUserId();
        $dataProvider = $this->getDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);

        $editorUserDirectAccountId = $dataProvider->loadEditorUserDirectAccountId($editorUserId, $isReadOnlyDb);

        if ($this->isMainAccount($editorUserDirectAccountId)) {
            $this->checkMultipleTenantInstallationForRegularAdminOfMainDomain($result);
            return $result;
        }

        $this->checkMultipleTenantInstallForRegularAdminOfPortalDomain($result);
        return $result;
    }

    /**
     * @param Result $result
     * @return Result
     */
    protected function checkMultipleTenantInstallationForRegularAdminOfMainDomain(Result $result): Result
    {
        $targetUserId = $result->targetUserId();
        $systemAccountId = $result->systemAccountId();
        $dataProvider = $this->getDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $shareUserInfo = (int)$this->fetchOptional(self::OP_SHARE_USER_INFO);

        /**
         * Main account admin, who visits main domain,
         * can Edit and View target user of main account domain.
         */
        $targetUserDirectAccountId = $dataProvider->loadTargetUserDirectAccountId($targetUserId, $isReadOnlyDb);
        if ($targetUserDirectAccountId === $systemAccountId) {
            return $result->addEditViewSuccess(
                Result::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_DIRECT_ACCOUNT_OF_MAIN_DOMAIN
            );
        }

        /**
         * When visiting main account is among collateral accounts of target user.
         */
        $isAmongTargetUserCollateralAccounts = $dataProvider->isAmongTargetUserCollateralAccounts($systemAccountId, $targetUserId, $isReadOnlyDb);
        if ($isAmongTargetUserCollateralAccounts) {
            /**
             * And "Share User Info" is "Edit", then we show editable form.
             */
            if ($shareUserInfo === Constants\ShareUserInfo::EDIT) {
                return $result->addEditViewSuccess(
                    Result::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_MAIN_DOMAIN_WHEN_SHARE_USER_INFO_IS_EDIT
                );
            }

            /**
             * and "Share User Info" is "View", then we show readable form.
             */
            if ($shareUserInfo === Constants\ShareUserInfo::VIEW) {
                return $result->addOnlyViewSuccess(
                    Result::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_MAIN_DOMAIN_WHEN_SHARE_USER_INFO_IS_VIEW
                );
            }

            /**
             * and "Share User Info" is "None", then we show readable form.
             */
            return $result->addOnlyViewSuccess(
                Result::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_MAIN_DOMAIN_WHEN_SHARE_USER_INFO_IS_NONE
            );
        }

        /**
         * When visiting main account is not among collateral accounts of target user,
         * then we show readable form independently of "Share User Info" option.
         */
        return $result->addOnlyViewSuccess(
            Result::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITHOUT_DIRECT_AND_COLLATERAL_ACCOUNT_TO_MAIN_DOMAIN
        );
    }

    /**
     * @param Result $result
     * @return Result
     */
    protected function checkMultipleTenantInstallForRegularAdminOfPortalDomain(Result $result): Result
    {
        $targetUserId = $result->targetUserId();
        $systemAccountId = $result->systemAccountId();
        $dataProvider = $this->getDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $shareUserInfo = (int)$this->fetchOptional(self::OP_SHARE_USER_INFO);

        /**
         * Portal domain admin, who visits own portal domain and accesses target user with own portal account,
         * can Edit and View any user independently of "Share User Info" option
         */
        $targetUserDirectAccountId = $dataProvider->loadTargetUserDirectAccountId($targetUserId, $isReadOnlyDb);
        if ($targetUserDirectAccountId === $systemAccountId) {
            return $result->addEditViewSuccess(
                Result::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_DIRECT_ACCOUNT_OF_THIS_PORTAL
            );
        }

        $isAmongTargetUserCollateralAccounts = $dataProvider->isAmongTargetUserCollateralAccounts($systemAccountId, $targetUserId, $isReadOnlyDb);
        if ($isAmongTargetUserCollateralAccounts) {
            /**
             * When visiting own portal account is among target user collateral accounts,
             * and "Share User Info" is "Edit"
             */
            if ($shareUserInfo === Constants\ShareUserInfo::EDIT) {
                return $result->addEditViewSuccess(
                    Result::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_EDIT
                );
            }

            /**
             * When visiting own portal account is among target user collateral accounts,
             * and "Share User Info" is "View"
             */
            if ($shareUserInfo === Constants\ShareUserInfo::VIEW) {
                return $result->addOnlyViewSuccess(
                    Result::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_VIEW
                );
            }

            /**
             * When visiting own portal account is among target user collateral accounts,
             * and "Share User Info" is "None", then cannot access
             */
            if ($shareUserInfo === Constants\ShareUserInfo::NONE) {
                return $result->addError(
                    Result::ERR_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_NONE
                );
            }
        }

        /**
         * When visiting own portal account is not among target user collateral accounts, then cannot access
         */
        return $result->addError(
            Result::ERR_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITHOUT_DIRECT_AND_COLLATERAL_ACCOUNT_OF_THIS_PORTAL
        );
    }

    /**
     * @param Result $result
     * @return Result
     */
    protected function checkSingleTenantInstallation(Result $result): Result
    {
        $targetUserId = $result->targetUserId();
        $dataProvider = $this->getDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);

        $targetUserDirectAccountId = $dataProvider->loadTargetUserDirectAccountId($targetUserId, $isReadOnlyDb);
        if (!$this->isMainAccount($targetUserDirectAccountId)) {
            /**
             * On single-tenant only main account target users can be managed.
             */
            return $result->addError(Result::ERR_SINGLE_TENANT_PORTAL_DOMAIN_TARGET_USER_NOT_AVAILABLE);
        }

        /**
         * Editor user can Edit and View any target user with main account.
         */
        return $result->addEditViewSuccess(Result::OK_SINGLE_TENANT_MAIN_DOMAIN_ADMIN_CAN_ACCESS_ANY_USER_OF_MAIN_DOMAIN);
    }

    protected function isMultipleTenant(): bool
    {
        return (bool)$this->fetchOptional(self::OP_IS_MULTIPLE_TENANT);
    }

    /**
     * Check, if account is main
     * @param int $accountId
     * @return bool
     */
    protected function isMainAccount(int $accountId): bool
    {
        return $accountId === (int)$this->fetchOptional(self::OP_MAIN_ACCOUNT_ID);
    }

    /**
     * @param DataProvider $dataProvider
     * @return $this
     * @internal
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    /**
     * @return DataProvider
     */
    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_IS_MULTIPLE_TENANT] = $optionals[self::OP_IS_MULTIPLE_TENANT]
            ?? static function (): bool {
                return (bool)cfg()->core->portal->enabled;
            };
        $optionals[self::OP_MAIN_ACCOUNT_ID] = $optionals[self::OP_MAIN_ACCOUNT_ID]
            ?? static function (): int {
                return (int)cfg()->core->portal->mainAccountId;
            };
        $optionals[self::OP_SHARE_USER_INFO] = $optionals[self::OP_SHARE_USER_INFO]
            ?? static function (): int {
                return (int)SettingsManager::new()->getForMain(Constants\Setting::SHARE_USER_INFO);
            };
        $this->setOptionals($optionals);
    }
}
