<?php
/**
 * SAM-10635: Decouple settings to "setting_shipping_auction_inc" table
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\SettingShippingAuctionInc\Internal;

use Sam\AuditTrail\AuditTrailHelperAwareTrait;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;
use SettingShippingAuctionInc;

/**
 * Class AuditTrailLogger
 * @package Sam\Observer\SettingShippingAuctionInc\Internal
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
        /** @var SettingShippingAuctionInc $settingShippingAuctionInc */
        $settingShippingAuctionInc = $subject->getEntity();
        $auditTrailHelper = $this->getAuditTrailHelper();
        $message = $auditTrailHelper->makeEntityModificationMessage($subject);
        if ($message !== '') {
            $section = $auditTrailHelper->autoSection();
            $message = 'System Parameters (' . $settingShippingAuctionInc->AccountId . ') modified: ' . $message;
            $editorUserId = $this->getUserLoader()->loadSystemUserId();
            $this->getAuditTrailLogger()
                ->setAccountId($settingShippingAuctionInc->AccountId)
                ->setEditorUserId($editorUserId)
                ->setEvent($message)
                ->setSection($section)
                ->setUserId($settingShippingAuctionInc->ModifiedBy)
                ->log();
        }
    }
}
