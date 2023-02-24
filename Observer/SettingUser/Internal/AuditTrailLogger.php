<?php
/**
 * SAM-10644: Decouple settings to "setting_user" table
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\SettingUser\Internal;

use Sam\AuditTrail\AuditTrailHelperAwareTrait;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;
use SettingUser;

/**
 * Class AuditTrailLogger
 * @package Sam\Observer\SettingUser\Internal
 */
class AuditTrailLogger extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuditTrailHelperAwareTrait;
    use AuditTrailLoggerAwareTrait;
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
    public function onCreate(EntityObserverSubject $subject): void
    {
        $this->log($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->log($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isModified();
    }

    /**
     * @param EntityObserverSubject $subject
     */
    protected function log(EntityObserverSubject $subject): void
    {
        /** @var SettingUser $settingUser */
        $settingUser = $subject->getEntity();
        $auditTrailHelper = $this->getAuditTrailHelper();
        $message = $auditTrailHelper->makeEntityModificationMessage($subject);
        if ($message !== '') {
            $section = $auditTrailHelper->autoSection();
            $message = 'System Parameters (' . $settingUser->AccountId . ') modified: ' . $message;
            $editorUserId = $this->getUserLoader()->loadSystemUserId();
            $this->getAuditTrailLogger()
                ->setAccountId($settingUser->AccountId)
                ->setEditorUserId($editorUserId)
                ->setEvent($message)
                ->setSection($section)
                ->setUserId($settingUser->ModifiedBy)
                ->log();
        }
    }
}
