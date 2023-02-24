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

namespace Sam\Lot\BuyerGroup\Load;

use BuyerGroupUser;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\BuyerGroupUser\BuyerGroupUserReadRepositoryCreateTrait;

/**
 * Class LotBuyerGroupLoader
 * @package Sam\Lot\BuyerGroup\Load
 */
class BuyerGroupUserLoader extends EntityLoaderBase
{
    use BuyerGroupUserReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns buyer group user where group and user are assigned.
     *
     * @param int $buyerGroupId
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return BuyerGroupUser|null
     */
    public function loadGroupMember(int $buyerGroupId, int $userId, bool $isReadOnlyDb = false): ?BuyerGroupUser
    {
        $groupMember = $this->createBuyerGroupUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterBuyerGroupId($buyerGroupId)
            ->filterUserId($userId)
            ->loadEntity();
        return $groupMember;
    }

    /**
     * Return the array of buyer_group.id where the user was assigned
     * @param int|null $userId user.id null means anonymous user and results with empty array []
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadBuyerGroupIdsForUser(?int $userId, bool $isReadOnlyDb = false): array
    {
        if (!$userId) {
            return [];
        }

        $rows = $this->createBuyerGroupUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterUserId($userId)
            ->select(['buyer_group_id'])
            ->loadRows();
        $buyerGroupIds = ArrayCast::arrayColumnInt($rows, 'buyer_group_id');
        return $buyerGroupIds;
    }

    /**
     * Load reference objects of User-to-BuyerGroup.
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return BuyerGroupUser[]
     */
    public function loadReferencesByUserId(?int $userId, bool $isReadOnlyDb = false): array
    {
        if (!$userId) {
            return [];
        }

        return $this->createBuyerGroupUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterUserId($userId)
            ->loadEntities();
    }
}
