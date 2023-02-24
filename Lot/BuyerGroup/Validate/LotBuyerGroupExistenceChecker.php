<?php
/**
 * SAM-4439 : Move lot's buyer group logic to Sam\Lot\BuyerGroup namespace
 * https://bidpath.atlassian.net/browse/SAM-4439
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/6/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\BuyerGroup\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyerGroup\BuyerGroupReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BuyerGroupUser\BuyerGroupUserReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotCategoryBuyerGroup\LotCategoryBuyerGroupReadRepositoryCreateTrait;

/**
 * Class LotBuyerGroupExistenceChecker
 * @package Sam\Lot\BuyerGroup\Validate
 */
class LotBuyerGroupExistenceChecker extends CustomizableClass
{
    use BuyerGroupUserReadRepositoryCreateTrait;
    use BuyerGroupReadRepositoryCreateTrait;
    use LotCategoryBuyerGroupReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check user is exist or not in assigned buyer group.
     * @param int $buyerGroupId
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existGroupMember(int $buyerGroupId, int $userId, bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyerGroupUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterBuyerGroupId($buyerGroupId)
            ->filterUserId($userId)
            ->exist();
    }

    /**
     * Check user is exist or not in buyer group where only name is assigned.
     * @param string $name
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByName(string $name, bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyerGroupReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterName($name)
            ->exist();
    }

    /**
     * Check buyer group exist or not.
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function exist(bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyerGroupReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->exist();
    }

    /**
     * Check lot category buyer group exist or not where group id and category id are assigned.
     * @param int $buyerGroupId
     * @param int $lotCategoryId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existGroupCategory(int $buyerGroupId, int $lotCategoryId, bool $isReadOnlyDb = false): bool
    {
        return $this->createLotCategoryBuyerGroupReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterBuyerGroupId($buyerGroupId)
            ->filterLotCategoryId($lotCategoryId)
            ->exist();
    }

    /**
     * Returns total active buyer group numbers.
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countAll(bool $isReadOnlyDb = false): int
    {
        return $this->createBuyerGroupReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->count();
    }

    /**
     * Count active users assigned to buyer's group.
     * @param int $buyerGroupId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countUsersInGroup(int $buyerGroupId, bool $isReadOnlyDb = false): int
    {
        return $this->createBuyerGroupUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterBuyerGroupId($buyerGroupId)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->count();
    }
}
