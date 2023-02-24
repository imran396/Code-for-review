<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbMessage;

use RtbMessage;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractRtbMessageWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist RtbMessage entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param RtbMessage $entity
     */
    public function save(RtbMessage $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist RtbMessage entity in DB on behalf of respective modifier user.
     * @param RtbMessage $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(RtbMessage $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist RtbMessage entity in DB on behalf of system user.
     * @param RtbMessage $entity
     */
    public function saveWithSystemModifier(RtbMessage $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist RtbMessage entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbMessage $entity
     */
    public function forceSave(RtbMessage $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist RtbMessage entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbMessage $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(RtbMessage $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbMessage entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param RtbMessage $entity
     */
    public function delete(RtbMessage $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete RtbMessage entity on behalf of respective modifier user.
     * @param RtbMessage $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(RtbMessage $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbMessage entity on behalf of system user.
     * @param RtbMessage $entity
     */
    public function deleteWithSystemModifier(RtbMessage $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
