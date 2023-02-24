<?php
/**
 * We assign creator and modifier user's reference in saving and deleting operations, not only for storing this value in DB,
 * but also with the purpose of passing it through to adjacent logic that is decoupled from the regular execution flow,
 * i.e. calls to services from observers or <Entity>::Delete() method.
 *
 * SAM-9363: Write repository generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\WriteRepository\Entity;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class WriteRepositoryBase
 * @package Sam\Storage\WriteRepository\Entity
 */
abstract class WriteRepositoryBase extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    private const DBAL_DISABLED_MESSAGE = 'DBAL is disabled by config option core->db->enabled';

    /**
     * @param object $entity
     */
    protected function saveEntity(object $entity): void
    {
        if (!$this->isDbEnabled()) {
            return;
        }

        if ($entity->__Modified) {
            $entity->Save();
        }
    }

    /**
     * @param object $entity
     * @param int $editorUserId
     */
    protected function saveEntityWithModifier(object $entity, int $editorUserId): void
    {
        /**
         * Update CreatedBy, ModifiedBy before $this->isDbEnabled(), so we can test changes.
         */
        if ($entity->__Modified) {
            if (!$entity->__Restored) {
                $entity->CreatedBy = $editorUserId;
            }
            $entity->ModifiedBy = $editorUserId;
        }

        if (!$this->isDbEnabled()) {
            return;
        }

        if ($entity->__Modified) {
            $entity->Save();
        }
    }

    /**
     * @param object $entity
     */
    protected function saveEntityWithSystemModifier(object $entity): void
    {
        if (!$this->isDbEnabled()) {
            return;
        }

        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * @param object $entity
     */
    protected function forceSaveEntity(object $entity): void
    {
        if (!$this->isDbEnabled()) {
            return;
        }

        $entity->Save(false, true);
    }

    /**
     * @param object $entity
     * @param int $editorUserId
     */
    protected function forceSaveEntityWithModifier(object $entity, int $editorUserId): void
    {
        /**
         * Update CreatedBy, ModifiedBy before $this->isDbEnabled(), so we can test changes.
         */
        if ($entity->__Modified) {
            if (!$entity->__Restored) {
                $entity->CreatedBy = $editorUserId;
            }
            $entity->ModifiedBy = $editorUserId;
        }

        if (!$this->isDbEnabled()) {
            return;
        }

        if ($entity->__Modified) {
            $entity->Save(false, true);
        }
    }

    /**
     * @param object $entity
     */
    protected function deleteEntity(object $entity): void
    {
        if (!$this->isDbEnabled()) {
            return;
        }

        $entity->Delete();
    }

    /**
     * We need to assign editor before entity hard-deleting,
     * because this user reference may be used by adjacent operations that are initiated on delete.
     * @param object $entity
     * @param int $editorUserId
     */
    protected function deleteEntityWithModifier(object $entity, int $editorUserId): void
    {
        if (!$this->isDbEnabled()) {
            return;
        }

        $entity->ModifiedBy = $editorUserId;
        $entity->Delete();
    }

    /**
     * @param object $entity
     */
    protected function deleteEntityWithSystemModifier(object $entity): void
    {
        if (!$this->isDbEnabled()) {
            return;
        }

        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    private function isDbEnabled(): bool
    {
        if ($this->cfg()->get('core->db->enabled')) {
            return true;
        }

        log_traceBackTrace(self::DBAL_DISABLED_MESSAGE);
        return false;
    }
}
