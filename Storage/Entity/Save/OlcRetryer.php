<?php
/**
 * We can segregate next false positive OLC exception cases:
 * 1) Entity data isn’t changed at all in concurrent processes, but saved with RowIndex increment.
 * 2) Entity’s same fields are changed in concurrent processes, but with the same values.
 * 3) Entity’s different fields are changed in concurrent processes.
 *
 * Correct OLC exception should be thrown, when:
 * 4) Entity’s same fields are changed in concurrent processes with different values
 *
 * SAM-5001: Entity save action retry handler
 * SAM-2126: DB code should effectively be prepared for failing transaction
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/27/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Save;

use QOptimisticLockingException;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class OlcRetryer
 * @package Sam\Storage\Entity\Save
 */
class OlcRetryer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param object $current - QCodo data class with Save() method
     * @param QOptimisticLockingException $e
     * @return mixed
     * @throw QOptimisticLockingException
     */
    public function retrySave(object $current, QOptimisticLockingException $e): mixed
    {
        if (!$this->cfg()->get('core->general->olcRetry->enabled')) {
            log_debug(
                "OLC exception raised. Its retrying logic is disabled by installation config setting "
                . "(core->general->olcRetry->enabled = false)"
            );
            throw $e;
        }

        if (in_array(get_class($current), $this->cfg()->get('core->general->olcRetry->disabledEntity')->toArray(), true)) {
            log_debug(
                "OLC exception raised. Its retrying logic is disabled by installation config setting "
                . "(core->general->olcRetry->disabledEntity = [" . get_class($current) . "])"
            );
            throw $e;
        }

        $class = get_class($current);
        log_debugBackTrace("Retrying of {$class} object save action, because of Optimistic Locking Constraint exception");
        $currentModified = $current->__Modified;
        $actual = clone $current;
        $actual->Reload();
        // Collect properties, whose values are different between current and db actual objects
        $differences = [];
        foreach ($currentModified as $property => $value) {
            $old = $currentModified[$property];
            $new = $current->$property;
            $act = $actual->$property;

            $isDifference = false;
            if (is_object($act) || is_object($new) || is_object($old)) {
                // When it is DateTime we want to compare equality instead references of objects
                /** @noinspection TypeUnsafeComparisonInspection */
                if ($act != $new && $act != $old) {
                    $isDifference = true;
                }
            } else {
                if ($act !== $new && $act !== $old) {
                    $isDifference = true;
                }
            }
            if ($isDifference) {
                $differences[$property] = ['old' => $old, 'new' => $new, 'actual' => $act];
            }
        }

        if (!$differences) {
            $newValues = [];
            foreach ($currentModified as $property => $value) {
                $actual->$property = $current->$property;
                $newValues[$property] = $actual->$property;
            }
            $result = $actual->Save();
            log_debug(
                "Save action of {$class} successfully retried after OLC re-check. Updated data"
                . composeSuffix($newValues)
            );
            return $result;
        }

        log_errorBackTrace(
            "Unable to retry save action, because currently saving object contains stale modified data."
            . "Thus OLC exception is expected and you need to fix logic. Class {$class}" . composeSuffix($differences)
        );
        throw $e;
    }
}
