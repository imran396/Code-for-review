<?php
/**
 * SAM-10626: Supply uniqueness for user fields: email
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\Email\Internal\Detect;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Lock\Email\Internal\Detect\UniqueEmailLockRequirementCheckingResult as Result;
use Sam\User\Load\Exception\CouldNotFindUser;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class UniqueEmailLockRequirementChecker
 * @package Sam\EntityMaker\User\Lock\Email\Internal
 */
class UniqueEmailLockRequirementChecker extends CustomizableClass
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

    public function check(?int $userId, ?string $email): Result
    {
        $result = Result::new()->construct($userId, $email);
        if (!$userId) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_NEW_RECORD_CREATED);
        }

        if (!$email) {
            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_EMAIL_ABSENT_IN_INPUT);
        }

        $user = $this->getUserLoader()->load($userId);
        if (!$user) {
            throw CouldNotFindUser::withId($userId);
        }
        if ($user->Email !== $email) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_EMAIL_CHANGED);
        }

        return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_EMAIL_NOT_CHANGED);
    }
}
