<?php
/**
 * SAM-4389: Problems with role permission check for lot custom field
 * This checking logic is actual in lot context. We can check, if user has permission for read access to lot related data (incl. lot custom fields)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/23/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class AccessCheckerBase
 * @package Sam\User\Access
 */
abstract class AccessCheckerBase extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /**
     * Check if user is active and not flagged as blocked
     * @param User|null $user
     * @return bool
     */
    protected function isAuthorizableUser(?User $user): bool
    {
        $is = false;
        if (
            $user
            && $user->UserStatusId === Constants\User::US_ACTIVE
            && $user->Flag !== Constants\User::FLAG_BLOCK
        ) {
            $is = true;
        }
        return $is;
    }

    /**
     * Check if user is active and not flagged as blocked
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    protected function isAuthorizableUserId(?int $userId, bool $isReadOnlyDb = false): bool
    {
        $user = $this->getUserLoader()->load($userId, $isReadOnlyDb);
        $is = $this->isAuthorizableUser($user);
        return $is;
    }
}
