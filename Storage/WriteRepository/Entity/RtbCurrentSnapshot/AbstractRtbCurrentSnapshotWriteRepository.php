<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbCurrentSnapshot;

use RtbCurrentSnapshot;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractRtbCurrentSnapshotWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist RtbCurrentSnapshot entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param RtbCurrentSnapshot $entity
     */
    public function save(RtbCurrentSnapshot $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist RtbCurrentSnapshot entity in DB on behalf of respective modifier user.
     * @param RtbCurrentSnapshot $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(RtbCurrentSnapshot $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist RtbCurrentSnapshot entity in DB on behalf of system user.
     * @param RtbCurrentSnapshot $entity
     */
    public function saveWithSystemModifier(RtbCurrentSnapshot $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist RtbCurrentSnapshot entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbCurrentSnapshot $entity
     */
    public function forceSave(RtbCurrentSnapshot $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist RtbCurrentSnapshot entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param RtbCurrentSnapshot $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(RtbCurrentSnapshot $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbCurrentSnapshot entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param RtbCurrentSnapshot $entity
     */
    public function delete(RtbCurrentSnapshot $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete RtbCurrentSnapshot entity on behalf of respective modifier user.
     * @param RtbCurrentSnapshot $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(RtbCurrentSnapshot $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete RtbCurrentSnapshot entity on behalf of system user.
     * @param RtbCurrentSnapshot $entity
     */
    public function deleteWithSystemModifier(RtbCurrentSnapshot $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
