<?php
/**
 * This service verifies correctness of user's direct account change.
 *
 * SAM-9177: User entity-maker - Account related issues for v3-4, v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\DirectAccount;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\EntityMaker\User\Validate\Internal\DirectAccount\DirectAccountChangeValidationResult as Result;
use Sam\EntityMaker\User\Validate\Internal\DirectAccount\DirectAccountChangeValidationInput as Input;
use Sam\EntityMaker\User\Validate\Internal\DirectAccount\Internal\Load\DataProviderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class DirectAccountChangeValidator
 * @package Sam\EntityMaker\User\Validate\Internal\DirectAccount
 */
class DirectAccountChangeValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Input $input
     * @param array $optionals = [
     *      self::OP_IS_READ_ONLY_DB => bool
     * ]
     * @return Result
     */
    public function validate(Input $input, array $optionals = []): Result
    {
        $result = Result::new()->construct();

        if (!$input->newAccountId) {
            return $result->addSuccess(Result::OK_ACCOUNT_CHANGE_NOT_REQUESTED);
        }

        if ($input->newAccountId === $input->oldAccountId) {
            return $result->addSuccess(Result::OK_NEW_ACCOUNT_IS_EQUAL_TO_OLD_ACCOUNT);
        }

        $isReadOnlyDb = (bool)($optionals[self::OP_IS_READ_ONLY_DB] ?? false);
        $dataProvider = $this->createDataProvider();

        $isFound = $dataProvider->existAccountById($input->newAccountId, $isReadOnlyDb);
        if (!$isFound) {
            return $result->addError(Result::ERR_ACCOUNT_NOT_FOUND);
        }

        $hasEditorUserPrivilegeForCrossDomainAdmin = $input->editorUserId
            ? $dataProvider->hasEditorUserSubPrivilegeForUserPrivileges($input->editorUserId, $isReadOnlyDb)
            : false;
        if ($hasEditorUserPrivilegeForCrossDomainAdmin) {
            // Cross-domain admin can create user with any account or can change it to any account
            return $result->addSuccess(Result::OK_CROSS_DOMAIN_ADMIN_CAN_CHANGE_ACCOUNT);
        }

        // When new user is created
        if (!$input->targetUserId) {
            // Absent editor means new user signs himself up
            if (!$input->editorUserId) {
                $mainAccountId = (int)$this->cfg()->get('core->portal->mainAccountId');
                // Explicitly defined direct account must be for main domain
                if ($input->newAccountId === $mainAccountId) {
                    return $result->addSuccess(Result::OK_NEW_USER_ACCOUNT_ON_SIGNUP_IS_MAIN);
                }
                return $result->addError(Result::ERR_NEW_USER_ACCOUNT_ON_SIGNUP_MUST_BE_MAIN);
            }

            /**
             * At this point editor, who is not cross-domain admin, is creating a new user.
             * We don't allow to define account explicitly by caller.
             */
            $result->addError(Result::ERR_ACCOUNT_ASSIGN_DENIED);
        }

        // We don't allow to change account by caller, when editor is not cross-domain admin
        return $result->addError(Result::ERR_ACCOUNT_CHANGE_DENIED);
    }
}
