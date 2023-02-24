<?php
/**
 * Check general privileges.
 * We load entity instead of existence check, because entities are cached in memory.
 *
 * Related tickets:
 * SAM-3560: Privilege checker class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           27 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Validate;

use Admin;
use Bidder;
use Consignor;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class GeneralChecker
 * @package Sam\User\Privilege\Validate
 */
class RoleChecker extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /** @var bool[] */
    private array $areAdmins = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check whether a user has admin privileges
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAdmin(?int $userId, bool $isReadOnlyDb = false): bool
    {
        $isAdmin = $this->areAdmins[$userId] ?? null;
        if ($isAdmin === null) {
            $admin = $this->getUserLoader()->loadAdmin($userId, $isReadOnlyDb);
            $this->areAdmins[$userId] = $admin instanceof Admin;
        }
        return $this->areAdmins[$userId];
    }

    /**
     * Check if user has admin role and is assigned to passed account
     * @param int|null $userId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAdminOfAccount(?int $userId, int $accountId, bool $isReadOnlyDb = false): bool
    {
        if (!$userId) {
            return false;
        }
        $isAdminOfAccount = false;
        $isAdmin = $this->isAdmin($userId, $isReadOnlyDb);
        if ($isAdmin) {
            $user = $this->getUserLoader()->load($userId, $isReadOnlyDb);
            $isAdminOfAccount = $user
                && $user->AccountId === $accountId;
        }
        return $isAdminOfAccount;
    }

    /**
     * Check whether a user has bidder privileges
     * @param int|null $userId null for anonymous user
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isBidder(?int $userId, bool $isReadOnlyDb = false): bool
    {
        if (!$userId) {
            return false;
        }
        $bidder = $this->getUserLoader()->loadBidder($userId, $isReadOnlyDb);
        $isBidder = $bidder instanceof Bidder;
        return $isBidder;
    }

    /**
     * Check whether a user has consignor privileges
     * @param int|null $userId null for anonymous
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isConsignor(?int $userId, bool $isReadOnlyDb = false): bool
    {
        if (!$userId) {
            return false;
        }
        $consignor = $this->getUserLoader()->loadConsignor($userId, $isReadOnlyDb);
        $isConsignor = $consignor instanceof Consignor;
        return $isConsignor;
    }
}
