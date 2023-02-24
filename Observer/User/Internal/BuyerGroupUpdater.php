<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\User\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\BuyerGroup\Member\LotBuyerGroupMemberManagerAwareTrait;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class SearchIndexUpdater
 * @package Sam\Observer\User
 * @internal
 */
class BuyerGroupUpdater extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use LotBuyerGroupMemberManagerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var User $user */
        $user = $subject->getEntity();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->getLotBuyerGroupMemberManager()->removeUserFromAllGroups($user->Id, $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var User $user */
        $user = $subject->getEntity();
        return $subject->isPropertyModified('UserStatusId')
            && $user->isDeleted();
    }
}
