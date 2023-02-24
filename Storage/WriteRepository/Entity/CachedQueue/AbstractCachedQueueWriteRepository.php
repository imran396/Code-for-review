<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CachedQueue;

use CachedQueue;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCachedQueueWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist CachedQueue entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param CachedQueue $entity
     */
    public function save(CachedQueue $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist CachedQueue entity in DB on behalf of respective modifier user.
     * @param CachedQueue $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(CachedQueue $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist CachedQueue entity in DB on behalf of system user.
     * @param CachedQueue $entity
     */
    public function saveWithSystemModifier(CachedQueue $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist CachedQueue entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CachedQueue $entity
     */
    public function forceSave(CachedQueue $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist CachedQueue entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CachedQueue $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(CachedQueue $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CachedQueue entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param CachedQueue $entity
     */
    public function delete(CachedQueue $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete CachedQueue entity on behalf of respective modifier user.
     * @param CachedQueue $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(CachedQueue $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CachedQueue entity on behalf of system user.
     * @param CachedQueue $entity
     */
    public function deleteWithSystemModifier(CachedQueue $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
