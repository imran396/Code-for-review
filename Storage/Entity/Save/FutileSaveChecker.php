<?php
/**
 * SAM-5102: Futile entity save check
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Storage\Entity\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\SupportLogger;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Class FutileSaveChecker
 * @package Sam\Storage\Entity\Save
 */
class FutileSaveChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SupportLoggerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return true if the object contains some new data to store and can be stored.
     * See core->entity->futileSave->skipUpdate option to switch this feature off.
     * Only existing entities may be skipped, always save new records.
     *
     * @param object $entity - QCodo data class with Save() method
     * @return bool
     */
    public function isReadyForSave(object $entity): bool
    {
        $isReady = true;
        if (
            $entity->__Restored
            && $this->cfg()->get('core->entity->futileSave->skipUpdate')
        ) {
            $modifiedFields = $entity->__Modified;
            $isReady = count($modifiedFields) > 0;
        }
        if (!$isReady) {
            $message = "Futile entity save action skipped";
            $this->log($entity, $message, 3);
        }
        return $isReady;
    }

    /**
     * @param object $entity
     * @param string $message
     * @param int $deep
     */
    public function log(object $entity, string $message, int $deep = 0): void
    {
        if (!$this->cfg()->get('core->entity->futileSave->log->enabled')) {
            return;
        }
        $class = get_class($entity);
        $fieldName = $entity->__PrimaryKeyField;
        $suffix = composeSuffix(["{$class}->{$fieldName}" => $entity->$fieldName]);
        $logMessage = $message . $suffix;
        $logLevel = (int)$this->cfg()->get('core->entity->futileSave->log->level');
        $isBackTrace = (bool)$this->cfg()->get('core->entity->futileSave->log->backTrace->enabled');
        $lineCount = $this->cfg()->get('core->entity->futileSave->log->backTrace->lineCount');
        $this->getSupportLogger()->log(
            $logLevel,
            $logMessage,
            $deep,
            [
                SupportLogger::OP_IS_BACKTRACE => $isBackTrace,
                SupportLogger::OP_BACKTRACE_LINE_COUNT => $lineCount,
            ]
        );
    }

}
