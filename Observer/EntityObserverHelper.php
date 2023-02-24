<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer;

use Sam\Core\Db\Schema\DbSchemaConstants;
use Sam\Core\Service\CustomizableClass;
use SplSubject;

/**
 * Class EntityObserverHelper
 * @package Sam\Observer
 */
class EntityObserverHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param EntityCreationObserverHandlerInterface[]|EntityUpdateObserverHandlerInterface[]|string[] $handlers
     * @param SplSubject $subject
     */
    public function runHandlers(array $handlers, SplSubject $subject): void
    {
        $entityObserverSubject = EntityObserverSubject::new()->construct($subject);
        $subject->__Restored
            ? $this->handleOnUpdate($handlers, $entityObserverSubject)
            : $this->handleOnCreate($handlers, $entityObserverSubject);
    }

    /**
     * @param array $handlers
     * @param EntityObserverSubject $subject
     */
    protected function handleOnCreate(array $handlers, EntityObserverSubject $subject): void
    {
        foreach ($handlers as $handler) {
            if ($handler instanceof EntityCreationObserverHandlerInterface) {
                if ($handler->isApplicable($subject)) {
                    log_trace(fn() => 'On Create observer called' . composeSuffix($this->logData($handler, $subject)));
                    $handler->onCreate($subject);
                } else {
                    log_trace(fn() => 'On Create observer is not applicable' . composeSuffix($this->logData($handler, $subject)));
                }
            }
        }
    }

    /**
     * @param array $handlers
     * @param EntityObserverSubject $subject
     */
    protected function handleOnUpdate(array $handlers, EntityObserverSubject $subject): void
    {
        foreach ($handlers as $handler) {
            if ($handler instanceof EntityUpdateObserverHandlerInterface) {
                if ($handler->isApplicable($subject)) {
                    log_trace(fn() => 'On Update observer called' . composeSuffix($this->logData($handler, $subject)));
                    $handler->onUpdate($subject);
                } else {
                    log_trace(fn() => 'On Update observer is not applicable' . composeSuffix($this->logData($handler, $subject)));
                }
            }
        }
    }

    /**
     * Produce log data.
     * @param EntityCreationObserverHandlerInterface|EntityUpdateObserverHandlerInterface $handler
     * @param EntityObserverSubject $subject
     * @return array
     */
    protected function logData(
        EntityCreationObserverHandlerInterface|EntityUpdateObserverHandlerInterface $handler,
        EntityObserverSubject $subject
    ): array {
        $entity = $subject->getEntity();
        $logData['entity'] = $entity::class;
        foreach (DbSchemaConstants::PK_TABLE_PROPERTY_MAP[$entity::class]['pk_properties'] as $property) {
            $logData[$property] = $entity->$property;
        }
        $logData['handler'] = $handler::class;
        return $logData;
    }
}
