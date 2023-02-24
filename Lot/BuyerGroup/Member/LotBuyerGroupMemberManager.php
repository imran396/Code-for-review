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

namespace Sam\Lot\BuyerGroup\Member;

use BuyerGroup;
use BuyerGroupUser;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\BuyerGroup\Load\BuyerGroupLoaderCreateTrait;
use Sam\Lot\BuyerGroup\Load\BuyerGroupUserLoaderCreateTrait;
use Sam\Lot\BuyerGroup\Validate\LotBuyerGroupExistenceCheckerAwareTrait;
use Sam\Storage\WriteRepository\Entity\BuyerGroup\BuyerGroupWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\BuyerGroupUser\BuyerGroupUserWriteRepositoryAwareTrait;

/**
 * Class LotBuyerGroupMemberManager
 * @package Sam\Lot\BuyerGroup\Member
 */
class LotBuyerGroupMemberManager extends CustomizableClass
{
    use BuyerGroupLoaderCreateTrait;
    use BuyerGroupUserLoaderCreateTrait;
    use BuyerGroupUserWriteRepositoryAwareTrait;
    use BuyerGroupWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use LotBuyerGroupExistenceCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Creating buyer group user if not exist and updating number of user.
     * @param int $buyerGroupId
     * @param int $userId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return BuyerGroupUser|null
     */
    public function add(
        int $buyerGroupId,
        int $userId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): ?BuyerGroupUser {
        if ($this->getLotBuyerGroupExistenceChecker()->existGroupMember($buyerGroupId, $userId, $isReadOnlyDb)) {
            // Do not add to group, because already added
            return null;
        }

        $buyerGroupUser = $this->createEntityFactory()->buyerGroupUser();
        $buyerGroupUser->Active = true;
        $buyerGroupUser->AddedBy = $editorUserId;
        $buyerGroupUser->AddedOn = $this->getCurrentDateUtc();
        $buyerGroupUser->BuyerGroupId = $buyerGroupId;
        $buyerGroupUser->UserId = $userId;
        $this->getBuyerGroupUserWriteRepository()->saveWithModifier($buyerGroupUser, $editorUserId);
        $this->recalcTotalsForBuyerGroupId($buyerGroupId, $editorUserId, $isReadOnlyDb);
        return $buyerGroupUser;
    }

    /**
     * Removes buyer group user by group id and user id and updating number of user.
     * @param int $buyerGroupId
     * @param int $userId
     * @param int|null $editorUserId
     * @param bool $isReadOnlyDb
     * @return BuyerGroupUser|null
     */
    public function remove(
        int $buyerGroupId,
        int $userId,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): ?BuyerGroupUser {
        $buyerGroupUser = $this->createBuyerGroupUserLoader()->loadGroupMember($buyerGroupId, $userId, $isReadOnlyDb);
        if ($buyerGroupUser) {
            $this->dropReference($buyerGroupUser, $editorUserId);
            $this->recalcTotalsForBuyerGroupId($buyerGroupId, $editorUserId, $isReadOnlyDb);
        }
        return $buyerGroupUser;
    }

    /**
     * Remove user from all his buyer groups.
     * @param int $userId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     */
    public function removeUserFromAllGroups(
        int $userId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): void {
        $buyerGroupUsers = $this->createBuyerGroupUserLoader()->loadReferencesByUserId($userId, $isReadOnlyDb);
        if (!$buyerGroupUsers) {
            return;
        }

        foreach ($buyerGroupUsers as $buyerGroupUser) {
            $this->dropReference($buyerGroupUser, $editorUserId);
        }
        $buyerGroupIds = ArrayHelper::toArrayByProperty($buyerGroupUsers, 'BuyerGroupId');
        $buyerGroups = $this->createBuyerGroupLoader()->loadByIds($buyerGroupIds, $isReadOnlyDb);
        foreach ($buyerGroups as $buyerGroup) {
            $this->recalcTotalsForBuyerGroup($buyerGroup, $editorUserId, $isReadOnlyDb);
        }
    }

    /**
     * Remove user from assignment in buyer group.
     * @param BuyerGroupUser $buyerGroupUser
     * @param int $editorUserId
     * @return BuyerGroupUser
     */
    protected function dropReference(BuyerGroupUser $buyerGroupUser, int $editorUserId): BuyerGroupUser
    {
        $buyerGroupUser->Active = false;
        $this->getBuyerGroupUserWriteRepository()->saveWithModifier($buyerGroupUser, $editorUserId);
        return $buyerGroupUser;
    }

    /**
     * @param int $buyerGroupId
     * @param int|null $editorUserId
     * @param bool $isReadOnlyDb
     */
    protected function recalcTotalsForBuyerGroupId(
        int $buyerGroupId,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): void {
        $buyerGroup = $this->createBuyerGroupLoader()->load($buyerGroupId);
        if ($buyerGroup) {
            $this->recalcTotalsForBuyerGroup($buyerGroup, $editorUserId, $isReadOnlyDb);
        }
    }

    /**
     * Update the number of user assigned to buyer_group
     * @param BuyerGroup $buyerGroup
     * @param int|null $editorUserId
     * @param bool $isReadOnlyDb
     * @return void
     */
    protected function recalcTotalsForBuyerGroup(
        BuyerGroup $buyerGroup,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): void {
        $buyerGroup->Users = $this->getLotBuyerGroupExistenceChecker()
            ->countUsersInGroup($buyerGroup->Id, $isReadOnlyDb);
        $this->getBuyerGroupWriteRepository()->saveWithModifier($buyerGroup, $editorUserId);
    }
}
