<?php

namespace Sam\User\Signup\Verify\Internal\Notify;

use Email_Template;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use User;

/**
 * Class Notifier
 * @package Sam\User\Signup\Verify\Internal\Notify
 */
class Notifier extends CustomizableClass
{
    use AuditTrailLoggerAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param User $user
     * @param int $editorUserId
     */
    public function logAuditTrail(User $user, int $editorUserId): void
    {
        $section = 'signup/verify-email';
        $event = $user->Username . " successfully verified his email " . $user->Email;
        $this->getAuditTrailLogger()
            ->setAccountId($user->AccountId)
            ->setEditorUserId($editorUserId)
            ->setEvent($event)
            ->setSection($section)
            ->setUserId($user->Id)
            ->log();
    }

    /**
     * @param User $user
     * @param int $editorUserId
     */
    public function noticeAccountReg(User $user, int $editorUserId): void
    {
        $emailManager = Email_Template::new()->construct(
            $this->getSystemAccountId(),
            Constants\EmailKey::ACCOUNT_REG,
            $editorUserId,
            [$user]
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
    }
}
