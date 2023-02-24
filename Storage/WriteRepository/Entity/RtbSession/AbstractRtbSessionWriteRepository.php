<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbSession;

use RtbSession;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractRtbSessionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist RtbSession entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param RtbSession $entity
     */
    public function save(RtbSession $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist RtbSession entity in DB on behalf of respective modifier user.
     * @param RtbSession $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(RtbSession $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist RtbSession entity in DB on behalf of system user.
     * @param RtbSession $entity
     */
    public function saveWithSystemModifier(RtbSession $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist RtbSession entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbSession $entity
     */
    public function forceSave(RtbSession $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist RtbSession entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbSession $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(RtbSession $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbSession entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param RtbSession $entity
     */
    public function delete(RtbSession $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete RtbSession entity on behalf of respective modifier user.
     * @param RtbSession $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(RtbSession $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbSession entity on behalf of system user.
     * @param RtbSession $entity
     */
    public function deleteWithSystemModifier(RtbSession $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
