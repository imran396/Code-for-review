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

namespace Sam\User\Log\Observe;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Log\Save\UserLoggerAwareTrait;

/**
 * Class UserLoggerBaseHandler
 * @package Sam\User\Log\Observe
 */
abstract class UserLoggerBaseHandler extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use UserLoaderAwareTrait;
    use UserLoggerAwareTrait;

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
        $properties = array_keys($this->getTrackedFields($subject));
        foreach ($properties as $property) {
            if ($this->isPropertyModified($property, $subject)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Save to log tracked fields for user.
     * We detect current editor's user id for user_log.admin_id
     *
     * @param EntityObserverSubject $subject
     */
    protected function log(EntityObserverSubject $subject): void
    {
        $changedUserId = $this->getEntityUserId($subject);
        $userLogger = $this->getUserLogger();
        $editorUserId = $this->detectModifierUserId();
        if ($userLogger->shouldLog($changedUserId, $editorUserId)) {
            $message = $this->getLoggedFieldMessage($subject);
            if ($message) {
                $userLogger->addToDb($message, $changedUserId, $editorUserId);
            }
        }
    }

    /**
     * @param EntityObserverSubject $subject
     * @return int
     */
    abstract protected function getEntityUserId(EntityObserverSubject $subject): int;

    /**
     * @param EntityObserverSubject $subject
     * @return array
     */
    abstract protected function getTrackedFields(EntityObserverSubject $subject): array;

    /**
     * Treat some values to be human readable
     *
     * @param EntityObserverSubject $subject
     * @param string $property Subject class property
     * @param bool $isOld True - We are treating old values (which was modified), False - new value
     * @return string
     */
    abstract protected function treat(EntityObserverSubject $subject, string $property, bool $isOld): string;

    /**
     * Return 'On' for true, 'Off' for false
     *
     * @param bool $value
     * @return string
     */
    protected function treatBoolean(bool $value): string
    {
        return $value ? 'On' : 'Off';
    }

    /**
     * @param int|string|null $value
     * @return string
     */
    protected function treatDate(int|string|null $value): string
    {
        if (!$value) {
            return '';
        }

        if (is_int($value)) {
            return (new DateTime())->setTimestamp($value)->format(Constants\Date::ISO);
        }

        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return (new DateTime())->setTimestamp($timestamp)->format(Constants\Date::ISO);
        }

        log_error('Unable to convert value to Unix timestamp: ' . composeSuffix(['value' => $value]));
        return '';
    }

    /**
     * Return message of tracked field changes for logging
     *
     * @param EntityObserverSubject $subject
     * @return string
     */
    protected function getLoggedFieldMessage(EntityObserverSubject $subject): string
    {
        $message = '';
        foreach ($this->getTrackedFields($subject) as $property => $name) {
            if ($this->isPropertyModified($property, $subject)) {
                $message .= $name . ': ';
                $oldValue = $this->treat($subject, $property, true);
                $newValue = $this->treat($subject, $property, false);
                $message .= '"' . $oldValue . '" => "' . $newValue . '"' . "\n";
            }
        }
        return $message;
    }

    /**
     * Return entity modifier user - he is either authorized user, or system user when the current user is anonymous or not defined.
     * @return int|null
     */
    protected function detectModifierUserId(): ?int
    {
        return $this->getEditorUserId() ?: $this->getUserLoader()->loadSystemUserId();
    }

    /**
     * @param string $property
     * @param EntityObserverSubject $subject
     * @return bool
     */
    protected function isPropertyModified(string $property, EntityObserverSubject $subject): bool
    {
        return $subject->isPropertyModified($property)
            && $subject->getOldPropertyValue($property) !== $subject->getEntity()->$property;
    }
}
