<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbCurrentIncrement;

use RtbCurrentIncrement;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractRtbCurrentIncrementWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist RtbCurrentIncrement entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param RtbCurrentIncrement $entity
     */
    public function save(RtbCurrentIncrement $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist RtbCurrentIncrement entity in DB on behalf of respective modifier user.
     * @param RtbCurrentIncrement $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(RtbCurrentIncrement $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist RtbCurrentIncrement entity in DB on behalf of system user.
     * @param RtbCurrentIncrement $entity
     */
    public function saveWithSystemModifier(RtbCurrentIncrement $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist RtbCurrentIncrement entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbCurrentIncrement $entity
     */
    public function forceSave(RtbCurrentIncrement $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist RtbCurrentIncrement entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbCurrentIncrement $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(RtbCurrentIncrement $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbCurrentIncrement entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param RtbCurrentIncrement $entity
     */
    public function delete(RtbCurrentIncrement $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete RtbCurrentIncrement entity on behalf of respective modifier user.
     * @param RtbCurrentIncrement $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(RtbCurrentIncrement $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbCurrentIncrement entity on behalf of system user.
     * @param RtbCurrentIncrement $entity
     */
    public function deleteWithSystemModifier(RtbCurrentIncrement $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
