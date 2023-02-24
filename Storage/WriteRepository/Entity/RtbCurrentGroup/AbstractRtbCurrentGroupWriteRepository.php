<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbCurrentGroup;

use RtbCurrentGroup;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractRtbCurrentGroupWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist RtbCurrentGroup entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param RtbCurrentGroup $entity
     */
    public function save(RtbCurrentGroup $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist RtbCurrentGroup entity in DB on behalf of respective modifier user.
     * @param RtbCurrentGroup $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(RtbCurrentGroup $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist RtbCurrentGroup entity in DB on behalf of system user.
     * @param RtbCurrentGroup $entity
     */
    public function saveWithSystemModifier(RtbCurrentGroup $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist RtbCurrentGroup entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbCurrentGroup $entity
     */
    public function forceSave(RtbCurrentGroup $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist RtbCurrentGroup entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbCurrentGroup $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(RtbCurrentGroup $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbCurrentGroup entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param RtbCurrentGroup $entity
     */
    public function delete(RtbCurrentGroup $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete RtbCurrentGroup entity on behalf of respective modifier user.
     * @param RtbCurrentGroup $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(RtbCurrentGroup $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbCurrentGroup entity on behalf of system user.
     * @param RtbCurrentGroup $entity
     */
    public function deleteWithSystemModifier(RtbCurrentGroup $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
