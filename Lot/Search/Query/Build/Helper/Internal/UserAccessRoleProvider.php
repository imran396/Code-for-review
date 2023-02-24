<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Search\Query\Build\Helper\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\User\Access\UnknownContextAccessCheckerAwareTrait;

/**
 * Class UserAccessRoleProvider
 * @package Sam\Lot\Search\Query\Build\Helper\Internal
 * @internal
 */
class UserAccessRoleProvider extends CustomizableClass
{
    use UnknownContextAccessCheckerAwareTrait;

    /**
     * @var array
     */
    protected static array $userAccessRolesCache = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $userId
     * @return array
     * @internal
     */
    public function get(?int $userId): array
    {
        if (!array_key_exists((int)$userId, self::$userAccessRolesCache)) {
            self::$userAccessRolesCache[(int)$userId] = $this->getUnknownContextAccessChecker()->detectRoles($userId)[0];
        }
        return self::$userAccessRolesCache[(int)$userId];
    }
}
