<?php
/**
 * This service checks what operations are available on Sales Staff assignment to target user.
 * This assignment is management by "Added by" field at User Edit page and relation is stored in user.added_by column.
 *
 * This service operates on field of User entity, but do not check Editor user privileges to access entity.
 * That should be done earlier, i.e. editor should be authorized user with admin role and with "Manage Users" privilege.
 *
 * All results of data provider can be predefined outside in caller with the purpose of optimization.
 *
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Access;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\User\AddedBy\Access\Internal\Load\DataProvider;
use Sam\User\AddedBy\Access\AddedByUserManagementAccessCheckResult as Result;

/**
 * Class AddedByUserManagementAccessChecker
 * @package Sam\User\AddedBy\Access
 */
class AddedByUserManagementAccessChecker extends CustomizableClass
{
    use OptionalsTrait;

    // Incoming values

    public const OP_ACTUAL_ADDED_BY_USER_ID = 'actualAddedByUserId'; // int
    public const OP_IS_EDITOR_USER_CROSS_DOMAIN_ADMIN = 'isEditorCrossDomainAdmin'; // bool
    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT; // bool
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_IS_TARGET_USER_CONSIGNOR = 'isConsignor'; // bool
    public const OP_IS_TARGET_USER_EDITABLE = 'isTargetUserEditable'; // bool
    public const OP_IS_TARGET_USER_VIEWABLE = 'isTargetUserViewable'; // bool
    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int
    public const OP_IS_LEGITIMATE_SALES_STAFF = 'isLegitimateSalesStaff'; // bool
    public const OP_IS_ACTUAL_ADDED_BY_AMONG_ACCESSIBLE_USERS_FOR_EDITOR = 'isActualAddedByAmongAllowedUsersForEditor'; // bool
    public const OP_IS_ACTUAL_ADDED_BY_VIEWABLE = 'isActualAddedByIsViewable'; // bool

    protected ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param int|null $targetUserId
     * @param int $editorUserId
     * @param int $systemAccountId
     * @return AddedByUserManagementAccessCheckResult
     */
    public function check(?int $targetUserId, int $editorUserId, int $systemAccountId): Result
    {
        $result = Result::new()->construct($targetUserId, $editorUserId, $systemAccountId);

        $isConsignor = (bool)$this->fetchOptional(self::OP_IS_TARGET_USER_CONSIGNOR, [$targetUserId]);
        if (!$isConsignor) {
            /**
             * Sales Staff agent can be assigned to target user with Consignor role only.
             */
            return $result->addError(Result::ERR_TARGET_USER_NOT_CONSIGNOR);
        }

        $isMultipleTenant = (bool)$this->fetchOptional(self::OP_IS_MULTIPLE_TENANT);
        if (!$isMultipleTenant) {
            /**
             * In case of single-tenant installation there is no reason to restrict assignment of sales staff agent,
             * when we are creating new consignor target user.
             */
            return $result->addSuccess(Result::OK_ALLOW_IN_SINGLE_TENANT_INSTALLATION);
        }

        if (!$targetUserId) {
            $isCrossDomainAdmin = (bool)$this->fetchOptional(self::OP_IS_EDITOR_USER_CROSS_DOMAIN_ADMIN);
            $isMainAccount = (int)$this->fetchOptional(self::OP_MAIN_ACCOUNT_ID) === $systemAccountId;
            if (
                $isCrossDomainAdmin
                && $isMainAccount
            ) {
                /**
                 * Cross-domain admin visits main domain on multiple-tenant installation - this is single scenario when consignor user can be created.
                 * At this point direct account of target user is not defined yet,
                 * thus impossible to define account restrictions for detection of available sales staff agents.
                 * We display 'Available after save' in place of the "Added By" selection.
                 */
                return $result->addError(Result::ERR_NEW_TARGET_USER_DIRECT_ACCOUNT_UNKNOWN);
            }
            /**
             * Otherwise, when we will allow to create consignor target user by regular admin,
             * then his direct account will be strictly defined, so we will be able to resolve account restriction for sales staff assignment.
             */
        }

        $isTargetUserViewable = (bool)$this->fetchOptional(
            self::OP_IS_TARGET_USER_VIEWABLE,
            [$targetUserId, $editorUserId, $systemAccountId]
        );
        if (!$isTargetUserViewable) {
            /**
             * "View" operation is restricted according user management access check.
             * This state doesn't happen in any scenario for our business rules.
             */
            return $result->addError(Result::ERR_TARGET_USER_NOT_VIEWABLE);
        }

        /**
         * Next are cases for multiple-tenant installation, where target user is existing consignor and his record is viewable at least.
         */

        $isTargetUserEditable = (bool)$this->fetchOptional(
            self::OP_IS_TARGET_USER_EDITABLE,
            [$targetUserId, $editorUserId, $systemAccountId]
        );

        $actualAddedByUserId = $this->fetchOptional(self::OP_ACTUAL_ADDED_BY_USER_ID, [$targetUserId]);
        $result->setActualAddedByUserId($actualAddedByUserId);

        /**
         * Process cases, when there is no relation for "Added By" sales staff user.
         */
        if (!$actualAddedByUserId) {
            /**
             * When there is no sales staff relation yet, then behavior depends on availability of "Edit" operation
             * $isTargetUserEditable = true, means sales staff agent can be assigned,
             * $isTargetUserEditable = false, means there is no info to show.
             */
            return $result->addSuccess(Result::OK_ACTUAL_ADDED_BY_EMPTY, $isTargetUserEditable);
        }

        return $this->verifyActualAssignment($isTargetUserEditable, $editorUserId, $systemAccountId, $result);
    }

    /**
     * Verify cases where "Added By" assignment exists.
     * @param bool $isTargetUserEditable
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param Result $result
     * @return Result
     */
    protected function verifyActualAssignment(
        bool $isTargetUserEditable,
        int $editorUserId,
        int $systemAccountId,
        Result $result
    ): Result {
        $isLegitimateSalesStaff = (bool)$this->fetchOptional(self::OP_IS_LEGITIMATE_SALES_STAFF, [$result->actualAddedByUserId]);
        if (!$isLegitimateSalesStaff) {
            /**
             * Existing "Added By" assignment is considered as not legitimate anymore,
             * when previously assigned agent was deleted or his "Sales Staff" privilege was revoked.
             */
            if ($isTargetUserEditable) {
                /**
                 * Since target user is editable, then not legitimate agent can be reassigned.
                 */
                return $result->addSuccess(Result::OK_ACTUAL_ADDED_BY_NOT_LEGITIMATE_AGENT_AND_EDIT_ALLOWED);
            }

            $isActualAddedByViewable = (bool)$this->fetchOptional(
                self::OP_IS_ACTUAL_ADDED_BY_VIEWABLE,
                [$result->actualAddedByUserId, $editorUserId, $systemAccountId]
            );

            /**
             * Since target user is not editable, but only viewable, then "Added By" agent cannot be reassigned.
             * Since actual "Added By" agent is not legitimate, but is viewable, then his info should be displayed.
             */
            if ($isActualAddedByViewable) {
                return $result->addError(Result::ERR_ACTUAL_ADDED_BY_NOT_LEGITIMATE_BUT_IS_VIEWABLE_AGENT_ALTHOUGH_TARGET_USER_EDIT_DENIED, true);
            }

            /**
             * Since target user is not editable, but only viewable, then "Added By" agent cannot be reassigned.
             * Since actual "Added By" agent is not legitimate agent and not viewable, then no info should be displayed.
             */
            return $result->addError(Result::ERR_ACTUAL_ADDED_BY_NOT_LEGITIMATE_AND_NOT_VIEWABLE_AGENT_AND_TARGET_USER_EDIT_DENIED);
        }

        $hasAccess = (bool)$this->fetchOptional(
            self::OP_IS_ACTUAL_ADDED_BY_AMONG_ACCESSIBLE_USERS_FOR_EDITOR,
            [
                $result->actualAddedByUserId,
                $result->targetUserId,
                $result->editorUserId,
                $result->systemAccountId,
            ]
        );

        if (!$hasAccess) {
            /**
             * Account restriction check is failed.
             */
            return $result->addError(Result::ERR_TARGET_USER_ACCOUNT_RESTRICTION);
        }

        return $result->addSuccess(Result::OK_ALLOW_BY_ACCOUNT_RESTRICTION, $isTargetUserEditable);
    }

    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
    }

    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_MULTIPLE_TENANT] = $optionals[self::OP_IS_MULTIPLE_TENANT]
            ?? static function (): bool {
                return (bool)cfg()->core->portal->enabled;
            };
        $optionals[self::OP_MAIN_ACCOUNT_ID] = $optionals[self::OP_MAIN_ACCOUNT_ID]
            ?? static function (): int {
                return (int)cfg()->core->portal->mainAccountId;
            };

        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $dataProvider = $this->getDataProvider();
        $optionals[self::OP_IS_TARGET_USER_EDITABLE] = $optionals[self::OP_IS_TARGET_USER_EDITABLE]
            ?? static function (?int $targetUserId, ?int $editorUserId, int $systemAccountId) use ($dataProvider, $isReadOnlyDb): bool {
                return $dataProvider->isTargetUserEditable($targetUserId, $editorUserId, $systemAccountId, $isReadOnlyDb);
            };
        $optionals[self::OP_IS_TARGET_USER_VIEWABLE] = $optionals[self::OP_IS_TARGET_USER_VIEWABLE]
            ?? static function (?int $targetUserId, ?int $editorUserId, int $systemAccountId) use ($dataProvider, $isReadOnlyDb): bool {
                return $dataProvider->isTargetUserViewable($targetUserId, $editorUserId, $systemAccountId, $isReadOnlyDb);
            };
        $optionals[self::OP_IS_TARGET_USER_CONSIGNOR] = $optionals[self::OP_IS_TARGET_USER_CONSIGNOR]
            ?? static function (?int $targetUserId) use ($dataProvider, $isReadOnlyDb): bool {
                return $dataProvider->isTargetUserConsignor($targetUserId, $isReadOnlyDb);
            };
        $optionals[self::OP_ACTUAL_ADDED_BY_USER_ID] = array_key_exists(self::OP_ACTUAL_ADDED_BY_USER_ID, $optionals)
            ? $optionals[self::OP_ACTUAL_ADDED_BY_USER_ID]
            : static function (?int $targetUserId) use ($dataProvider, $isReadOnlyDb): ?int {
                return $dataProvider->loadActualAddedByUserId($targetUserId, $isReadOnlyDb);
            };
        $optionals[self::OP_IS_EDITOR_USER_CROSS_DOMAIN_ADMIN] = $optionals[self::OP_IS_EDITOR_USER_CROSS_DOMAIN_ADMIN]
            ?? static function (?int $editorUserId) use ($dataProvider, $isReadOnlyDb): bool {
                return $dataProvider->isEditorUserCrossDomainAdmin($editorUserId, $isReadOnlyDb);
            };
        $optionals[self::OP_IS_LEGITIMATE_SALES_STAFF] = $optionals[self::OP_IS_LEGITIMATE_SALES_STAFF]
            ?? static function (int $actualAddedByUserId) use ($dataProvider, $isReadOnlyDb): bool {
                return $dataProvider->isLegitimateSalesStaff($actualAddedByUserId, $isReadOnlyDb);
            };
        $optionals[self::OP_IS_ACTUAL_ADDED_BY_AMONG_ACCESSIBLE_USERS_FOR_EDITOR] = $optionals[self::OP_IS_ACTUAL_ADDED_BY_AMONG_ACCESSIBLE_USERS_FOR_EDITOR]
            ?? static function (int $actualAddedByUserId, int $targetUserId, int $editorUserId, int $systemAccountId) use ($dataProvider, $isReadOnlyDb): bool {
                return $dataProvider->isActualAddedByAmongAccessibleUsersForEditor($actualAddedByUserId, $targetUserId, $editorUserId, $systemAccountId, $isReadOnlyDb);
            };
        $optionals[self::OP_IS_ACTUAL_ADDED_BY_VIEWABLE] = $optionals[self::OP_IS_ACTUAL_ADDED_BY_VIEWABLE]
            ?? static function (int $actualAddedByUserId, int $editorUserId, int $systemAccountId) use ($dataProvider, $isReadOnlyDb): bool {
                return $dataProvider->isActualAddedByViewable($actualAddedByUserId, $editorUserId, $systemAccountId, $isReadOnlyDb);
            };
        $this->setOptionals($optionals);
    }
}
