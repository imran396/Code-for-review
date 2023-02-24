<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SyncNamespace;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SyncNamespace;

abstract class AbstractSyncNamespaceWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SyncNamespace entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SyncNamespace $entity
     */
    public function save(SyncNamespace $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SyncNamespace entity in DB on behalf of respective modifier user.
     * @param SyncNamespace $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SyncNamespace $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SyncNamespace entity in DB on behalf of system user.
     * @param SyncNamespace $entity
     */
    public function saveWithSystemModifier(SyncNamespace $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SyncNamespace entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SyncNamespace $entity
     */
    public function forceSave(SyncNamespace $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SyncNamespace entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SyncNamespace $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SyncNamespace $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SyncNamespace entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SyncNamespace $entity
     */
    public function delete(SyncNamespace $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SyncNamespace entity on behalf of respective modifier user.
     * @param SyncNamespace $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SyncNamespace $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SyncNamespace entity on behalf of system user.
     * @param SyncNamespace $entity
     */
    public function deleteWithSystemModifier(SyncNamespace $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
