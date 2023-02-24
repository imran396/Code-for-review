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

namespace Sam\Observer\UserBilling\Internal;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAccount\UserAccountWriteRepositoryAwareTrait;
use Sam\User\Account\Load\UserAccountLoaderAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use UserBilling;

/**
 * Class UserAccountFlagHandler
 * @package Sam\Observer\UserBilling
 * @internal
 */
class UserAccountFlagHandler extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use EntityFactoryCreateTrait;
    use SystemAccountAwareTrait;
    use UserAccountLoaderAwareTrait;
    use UserAccountWriteRepositoryAwareTrait;
    use UserFlaggingAwareTrait;

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
    public function onCreate(EntityObserverSubject $subject): void
    {
        $this->handle($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->handle($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isPropertyModified('CcNumber')
            && $this->isPortalSystemAccount();
    }

    /**
     * @param EntityObserverSubject $subject
     */
    protected function handle(EntityObserverSubject $subject): void
    {
        /** @var UserBilling $userBilling */
        $userBilling = $subject->getEntity();
        $portalAccountId = $this->getSystemAccountId();
        $userAccount = $this->getUserAccountLoader()->load($userBilling->UserId, $portalAccountId);
        if (!$userAccount) {
            $userAccount = $this->createEntityFactory()->userAccount();
            $userAccount->UserId = $userBilling->UserId;
            $userAccount->AccountId = $portalAccountId;
            $this->getUserAccountWriteRepository()->saveWithSystemModifier($userAccount);
        }
        $flag = $this->getUserFlagging()->checkUserAccountFlag($userAccount);
        $userAccount->Flag = $flag;
        $this->getUserAccountWriteRepository()->saveWithSystemModifier($userAccount);
    }
}
