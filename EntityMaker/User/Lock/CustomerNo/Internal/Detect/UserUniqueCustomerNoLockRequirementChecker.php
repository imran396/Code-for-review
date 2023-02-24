<?php
/**
 * SAM-10627: Supply uniqueness for user fields: customer#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect\UserUniqueCustomerNoLockRequirementCheckingResult as Result;
use Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect\UserUniqueCustomerNoLockRequirementCheckingInput as Input;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Load\Exception\CouldNotFindUser;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class UserUniqueCustomerNoLockDetector
 * @package Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect
 */
class UserUniqueCustomerNoLockRequirementChecker extends CustomizableClass
{
    use SettingsManagerAwareTrait;
    use UserLoaderAwareTrait;

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
     * @return Result
     */
    public function check(Input $input): Result
    {
        $result = Result::new()->construct($input);

        /**
         * Checking new user creation cases.
         */
        if (!$input->userId) {
            if (
                !$input->isSetCustomerNo
                || (int)$input->customerNo === 0
            ) {
                $isAutoIncrementCustomerNum = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::AUTO_INCREMENT_CUSTOMER_NUM);
                if ($isAutoIncrementCustomerNum) {
                    return $result->addSuccess(Result::OK_LOCK_WHEN_CREATE_USER_WITH_EMPTY_INPUT_BECAUSE_GENERATION_ENABLED);
                }
                return $result->addInfo(Result::INFO_DO_NOT_LOCK_WHEN_CREATE_USER_WITH_EMPTY_INPUT_BECAUSE_GENERATION_DISABLED);
            }

            return $result->addSuccess(Result::OK_LOCK_WHEN_CREATE_USER_WITH_FILLED_INPUT);
        }

        /**
         * Checking the existing user editing cases.
         */

        if (!$input->isSetCustomerNo) {
            return $result->addInfo(Result::INFO_DO_NOT_LOCK_WHEN_EDIT_USER_BECAUSE_CUSTOMER_NO_ABSENT_IN_INPUT);
        }

        $user = $this->getUserLoader()->load($input->userId);
        if (!$user) {
            throw CouldNotFindUser::withId($input->userId);
        }

        $customerNo = Cast::toInt($input->customerNo);
        if ($customerNo !== $user->CustomerNo) {
            return $result->addSuccess(Result::OK_LOCK_WHEN_EDIT_USER_BECAUSE_CUSTOMER_NO_MODIFIED);
        }

        return $result->addInfo(Result::INFO_DO_NOT_LOCK_WHEN_EDIT_USER_BECAUSE_CUSTOMER_NO_NOT_MODIFIED);
    }
}
