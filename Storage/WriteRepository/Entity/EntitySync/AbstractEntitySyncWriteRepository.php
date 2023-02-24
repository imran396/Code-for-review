<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\EntitySync;

use EntitySync;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractEntitySyncWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist EntitySync entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param EntitySync $entity
     */
    public function save(EntitySync $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist EntitySync entity in DB on behalf of respective modifier user.
     * @param EntitySync $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(EntitySync $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist EntitySync entity in DB on behalf of system user.
     * @param EntitySync $entity
     */
    public function saveWithSystemModifier(EntitySync $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist EntitySync entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param EntitySync $entity
     */
    public function forceSave(EntitySync $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist EntitySync entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param EntitySync $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(EntitySync $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete EntitySync entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param EntitySync $entity
     */
    public function delete(EntitySync $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete EntitySync entity on behalf of respective modifier user.
     * @param EntitySync $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(EntitySync $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete EntitySync entity on behalf of system user.
     * @param EntitySync $entity
     */
    public function deleteWithSystemModifier(EntitySync $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
