<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\Auction\Internal;

use Auction;
use Sam\AuditTrail\AuditTrailHelperAwareTrait;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuditTrailLogger
 * @package Sam\Observer\Auction
 * @internal
 */
class AuditTrailLogger extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuditTrailHelperAwareTrait;
    use AuditTrailLoggerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var string[]
     */
    protected static array $excludeFromAudit = [
        'EndDate',
        'ModifiedOn',
    ];

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
        /** @var Auction $auction */
        $auction = $subject->getEntity();

        $message = '';
        $auditTrailHelper = $this->getAuditTrailHelper();
        foreach ($subject->getOldPropertyValues() as $k => $v) {
            if (in_array($k, self::$excludeFromAudit, true)) {
                continue;
            }
            $new = $auditTrailHelper->valToString($auction->$k);
            $old = $auditTrailHelper->valToString($v);
            if ($message !== '') {
                $message .= '; ';
            }
            $message .= $k . ': ';
            $message .= (($new !== $old) ? $old . '=>' : '') . $new;
        }
        if ($message !== '') {
            $section = $auditTrailHelper->autoSection();
            $message = 'Auction (' . $auction->Id . ',' . $auction->AccountId . ') modified: ' . $message;
            $editorUserId = $this->getUserLoader()->loadSystemUserId();
            $this->getAuditTrailLogger()
                ->setAccountId($auction->AccountId)
                ->setEditorUserId($editorUserId)
                ->setEvent($message)
                ->setSection($section)
                ->setUserId($auction->ModifiedBy)
                ->log();
        }
    }
}
