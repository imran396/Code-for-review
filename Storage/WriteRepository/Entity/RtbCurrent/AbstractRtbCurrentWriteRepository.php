<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbCurrent;

use RtbCurrent;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractRtbCurrentWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist RtbCurrent entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param RtbCurrent $entity
     */
    public function save(RtbCurrent $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist RtbCurrent entity in DB on behalf of respective modifier user.
     * @param RtbCurrent $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(RtbCurrent $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist RtbCurrent entity in DB on behalf of system user.
     * @param RtbCurrent $entity
     */
    public function saveWithSystemModifier(RtbCurrent $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist RtbCurrent entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbCurrent $entity
     */
    public function forceSave(RtbCurrent $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist RtbCurrent entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbCurrent $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(RtbCurrent $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbCurrent entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param RtbCurrent $entity
     */
    public function delete(RtbCurrent $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete RtbCurrent entity on behalf of respective modifier user.
     * @param RtbCurrent $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(RtbCurrent $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbCurrent entity on behalf of system user.
     * @param RtbCurrent $entity
     */
    public function deleteWithSystemModifier(RtbCurrent $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
