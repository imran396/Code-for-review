<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/01/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\User\Validate;

use Sam\Application\Controller\Admin\User\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Application\Controller\Admin\User\Validate\UserControllerValidationResult as Result;

/**
 * Class UserControllerValidator
 * @package Sam\Application\Controller\Admin\User\Validate
 */
class UserControllerValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use OptionalsTrait;

    // --- Incoming values ---

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return static
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
     * Validate/Check if User ID exists, and not archived or deleted
     * @param int|null $targetUserId null means that a new user is being created
     * @param int|null $editorUserId null means anonymous editor user, he is denied at higher layer of ACL check
     * @param int $systemAccountId
     * @return Result
     */
    public function validate(?int $targetUserId, ?int $editorUserId, int $systemAccountId): Result
    {
        $result = Result::new()->construct($targetUserId, $editorUserId, $systemAccountId);
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dataProvider = $this->createDataProvider();

        if (!$dataProvider->hasPrivilegeForManageUsers($editorUserId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_EDITOR_USER_ABSENT_PRIVILEGE_FOR_MANAGE_USERS);
        }

        /**
         * Do not perform target user validation, when creating a new user
         */
        if (!$targetUserId) {
            return $result->addSuccess(Result::OK_CREATE_NEW_USER);
        }

        /**
         * Validate target user passed by id
         */
        $result = $this->validateTargetUser($targetUserId, $editorUserId, $systemAccountId, $result);
        if ($result->hasError()) {
            return $result;
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    // --- Internal logic

    /**
     * Validate target user passed by id
     * @param int $targetUserId
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param UserControllerValidationResult $result
     * @return UserControllerValidationResult
     */
    protected function validateTargetUser(int $targetUserId, int $editorUserId, int $systemAccountId, Result $result): Result
    {
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dataProvider = $this->createDataProvider();

        $targetUser = $dataProvider->loadTargetUser($targetUserId, $isReadOnlyDb);
        if (!$targetUser) {
            return $result->addError(Result::ERR_TARGET_USER_NOT_FOUND_BY_ID);
        }

        if ($targetUser->isDeleted()) {
            return $result->addError(Result::ERR_TARGET_USER_DELETED);
        }

        if (!$dataProvider->isTargetUserAccountAvailable($targetUser->AccountId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_TARGET_USER_ACCOUNT_NOT_FOUND);
        }

        $accessResult = $dataProvider->checkUserManagementAccess($targetUserId, $editorUserId, $systemAccountId, $isReadOnlyDb);
        if ($accessResult->hasError()) {
            return $result->addError(Result::ERR_TARGET_USER_ACCESS_DENIED, null, $accessResult->logData());
        }

        return $result;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $this->setOptionals($optionals);
    }
}
