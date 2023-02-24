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

use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UserBilling;

/**
 * Class UserFlagHandler
 * @package Sam\Observer\UserBilling\
 * @internal
 */
class UserFlagHandler extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use UserFlaggingAwareTrait;
    use UserLoaderAwareTrait;
    use UserWriteRepositoryAwareTrait;

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
        return $subject->isPropertyModified('CcNumber');
    }

    /**
     * @param EntityObserverSubject $subject
     */
    protected function handle(EntityObserverSubject $subject): void
    {
        /** @var UserBilling $userBilling */
        $userBilling = $subject->getEntity();
        $user = $this->getUserLoader()->load($userBilling->UserId);
        if (!$user) {
            log_error("Available user not found, when processing user flag" . composeSuffix(['u' => $userBilling->UserId]));
            return;
        }

        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $newUserFlag = $this->getUserFlagging()->checkUserFlag($user, $userBilling, $editorUserId);
        $user->Flag = $newUserFlag;
        $this->getUserWriteRepository()->saveWithSystemModifier($user);
    }
}
