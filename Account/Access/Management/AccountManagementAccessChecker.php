<?php
/**
 * SAM-9594: Account management access checking
 * SAM-7650: Route and menu adjustments of Settings / System Parameters section
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Access\Management;

use Sam\Account\Access\Management\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Account\Access\Management\AccountManagementAccessCheckingResult as Result;

/**
 * Class AccountManagementAccessChecker
 * @package Sam\Account\Access\Management
 */
class AccountManagementAccessChecker extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * --- Access checking operations that returns true/false and fail on the first error ---
     */

    /**
     * Check general access to account management function independently of concrete account entity.
     * Quick check that fails on the first found error.
     *
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasAccess(
        ?int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        $result = $this->checkAccess($editorUserId, $systemAccountId, true, $isReadOnlyDb);
        return $result->hasSuccess();
    }

    /**
     * Check access for create or edit operation.
     * If target account id is set, then we check access for edit operation.
     * If target account id is not set, then we check access for create operation.
     *
     * @param int|null $targetAccountId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasAccessForCreateOrEdit(
        ?int $targetAccountId,
        ?int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        $result = $this->checkAccessForCreateOrEdit(
            $targetAccountId,
            $editorUserId,
            $systemAccountId,
            true,
            $isReadOnlyDb
        );
        return $result->hasSuccess();
    }

    /**
     * Check access for create operation.
     *
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasAccessForCreate(
        ?int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        $result = $this->checkAccessForCreate(
            $editorUserId,
            $systemAccountId,
            true,
            $isReadOnlyDb
        );
        return $result->hasSuccess();
    }

    /**
     * Check access for edit operation.
     *
     * @param int|null $targetAccountId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasAccessForEdit(
        ?int $targetAccountId,
        ?int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        $result = $this->checkAccessForEdit(
            $targetAccountId,
            $editorUserId,
            $systemAccountId,
            true,
            $isReadOnlyDb
        );
        return $result->hasSuccess();
    }

    /**
     * Check access for delete operation.
     *
     * @param int|null $targetAccountId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasAccessForDelete(
        ?int $targetAccountId,
        ?int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        $result = $this->checkAccessForDelete(
            $targetAccountId,
            $editorUserId,
            $systemAccountId,
            $isReadOnlyDb
        );
        return $result->hasSuccess();
    }

    /**
     * --- Access checking operations that return result-objects ---
     */

    /**
     * General access check to account management functionality independently of the concrete account entity.
     *
     * @param int|null $editorUserId null for anonymous user, whose access must be rejected.
     * @param int $systemAccountId
     * @param bool $isBreakOnFirstError def: true, Disable to collect all fails.
     * @param bool $isReadOnlyDb
     * @return Result result-object with verification details
     */
    public function checkAccess(
        ?int $editorUserId,
        int $systemAccountId,
        bool $isBreakOnFirstError = false,
        bool $isReadOnlyDb = false
    ): Result {
        return $this->internalCheckAccess(
            Result::new()->construct(),
            $editorUserId,
            $systemAccountId,
            $isBreakOnFirstError,
            $isReadOnlyDb
        );
    }

    /**
     * @param int|null $targetAccountId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isBreakOnFirstError
     * @param bool $isReadOnlyDb
     * @return AccountManagementAccessCheckingResult
     */
    public function checkAccessForCreateOrEdit(
        ?int $targetAccountId,
        ?int $editorUserId,
        int $systemAccountId,
        bool $isBreakOnFirstError = false,
        bool $isReadOnlyDb = false
    ): Result {
        if ($targetAccountId) {
            return $this->checkAccessForEdit(
                $targetAccountId,
                $editorUserId,
                $systemAccountId,
                $isBreakOnFirstError,
                $isReadOnlyDb
            );
        }
        return $this->checkAccessForCreate(
            $editorUserId,
            $systemAccountId,
            $isBreakOnFirstError,
            $isReadOnlyDb
        );
    }

    /**
     * Access check for create operation.
     *
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isBreakOnFirstError
     * @param bool $isReadOnlyDb
     * @return AccountManagementAccessCheckingResult
     */
    public function checkAccessForCreate(
        ?int $editorUserId,
        int $systemAccountId,
        bool $isBreakOnFirstError = false,
        bool $isReadOnlyDb = false
    ): Result {
        return $this->checkAccess($editorUserId, $systemAccountId, $isBreakOnFirstError, $isReadOnlyDb);
    }

    /**
     * Access check for edit operation of concrete entity with definite id.
     *
     * @param int|null $targetAccountId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isBreakOnFirstError
     * @param bool $isReadOnlyDb
     * @return AccountManagementAccessCheckingResult
     */
    public function checkAccessForEdit(
        ?int $targetAccountId,
        ?int $editorUserId,
        int $systemAccountId,
        bool $isBreakOnFirstError = false,
        bool $isReadOnlyDb = false
    ): Result {
        $result = Result::new()->construct();
        return $this->internalCheckAccessForEdit(
            $result,
            $targetAccountId,
            $editorUserId,
            $systemAccountId,
            $isBreakOnFirstError,
            $isReadOnlyDb
        );
    }

    /**
     * Access check for delete operation of concrete entity with definite id.
     *
     * @param int|null $targetAccountId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isBreakOnFirstError
     * @param bool $isReadOnlyDb
     * @return AccountManagementAccessCheckingResult
     */
    public function checkAccessForDelete(
        ?int $targetAccountId,
        ?int $editorUserId,
        int $systemAccountId,
        bool $isBreakOnFirstError = false,
        bool $isReadOnlyDb = false
    ): Result {
        $result = Result::new()->construct();

        /**
         * account.id must be provided for entity deletion
         */
        if (!$targetAccountId) {
            $result->addError(Result::ERR_ACCOUNT_ID_NOT_DEFINED);
            if ($isBreakOnFirstError) {
                return $result;
            }
        }

        return $this->internalCheckAccessForEdit(
            $result,
            $targetAccountId,
            $editorUserId,
            $systemAccountId,
            $isBreakOnFirstError,
            $isReadOnlyDb
        );
    }

    /**
     * --- Internal logic ---
     */

    /**
     * Access check for edit operation of concrete entity with definite id.
     *
     * @param AccountManagementAccessCheckingResult $result
     * @param int|null $targetAccountId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isBreakOnFirstError
     * @param bool $isReadOnlyDb
     * @return AccountManagementAccessCheckingResult
     */
    protected function internalCheckAccessForEdit(
        Result $result,
        ?int $targetAccountId,
        ?int $editorUserId,
        int $systemAccountId,
        bool $isBreakOnFirstError = false,
        bool $isReadOnlyDb = false
    ): Result {
        /**
         * check active entity existence for definite account.id only
         */
        if ($targetAccountId) {
            $isFound = $this->createDataProvider()->existAccountById($targetAccountId, $isReadOnlyDb);
            if (!$isFound) {
                $result->addError(Result::ERR_ACCOUNT_NOT_FOUND_BY_ID);
                if ($isBreakOnFirstError) {
                    return $result;
                }
            }
        }

        return $this->internalCheckAccess(
            $result,
            $editorUserId,
            $systemAccountId,
            $isBreakOnFirstError,
            $isReadOnlyDb
        );
    }

    /**
     * General access check to account management functionality independently of the concrete account entity.
     *
     * @param AccountManagementAccessCheckingResult $result
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isBreakOnFirstError
     * @param bool $isReadOnlyDb
     * @return AccountManagementAccessCheckingResult
     */
    protected function internalCheckAccess(
        Result $result,
        ?int $editorUserId,
        int $systemAccountId,
        bool $isBreakOnFirstError = false,
        bool $isReadOnlyDb = false
    ): Result {
        $dataProvider = $this->createDataProvider();
        if ($dataProvider->isSingleTenant()) {
            $result->addError(Result::ERR_DENIED_FOR_SINGLE_TENANT_INSTALLATION);
            if ($isBreakOnFirstError) {
                return $result;
            }
        }

        if ($dataProvider->isPortalAccount($systemAccountId)) {
            $result->addError(Result::ERR_DENIED_FOR_PORTAL_SYSTEM_ACCOUNT);
            if ($isBreakOnFirstError) {
                return $result;
            }
        }

        $hasPrivilege = $dataProvider->hasPrivilegeForManageSettings($editorUserId, $isReadOnlyDb);
        if (!$hasPrivilege) {
            $result->addError(Result::ERR_ABSENT_MANAGE_SETTINGS_PRIVILEGE);
            if ($isBreakOnFirstError) {
                return $result;
            }
        }
        return $result;
    }
}
