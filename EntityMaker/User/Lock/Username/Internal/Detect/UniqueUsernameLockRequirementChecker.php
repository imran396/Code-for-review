<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\Username\Internal\Detect;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Lock\Username\Internal\Detect\UniqueUsernameLockRequirementCheckingResult as Result;
use Sam\User\Load\Exception\CouldNotFindUser;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class UniqueUsernameLockRequirementChecker
 * @package Sam\EntityMaker\User\Lock\Username\Internal
 */
class UniqueUsernameLockRequirementChecker extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function check(?int $userId, ?string $username): Result
    {
        $result = Result::new()->construct($userId, $username);
        if (!$userId) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_NEW_RECORD_CREATED);
        }

        if (!$username) {
            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_USERNAME_ABSENT_IN_INPUT);
        }

        $user = $this->getUserLoader()->load($userId);
        if (!$user) {
            throw CouldNotFindUser::withId($userId);
        }
        if ($user->Username !== $username) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_USERNAME_CHANGED);
        }

        return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_USERNAME_NOT_CHANGED);
    }
}
