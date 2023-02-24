<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserLog;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserLog;

abstract class AbstractUserLogWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserLog entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserLog $entity
     */
    public function save(UserLog $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserLog entity in DB on behalf of respective modifier user.
     * @param UserLog $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserLog $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserLog entity in DB on behalf of system user.
     * @param UserLog $entity
     */
    public function saveWithSystemModifier(UserLog $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserLog entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserLog $entity
     */
    public function forceSave(UserLog $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserLog entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserLog $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserLog $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserLog entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserLog $entity
     */
    public function delete(UserLog $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserLog entity on behalf of respective modifier user.
     * @param UserLog $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserLog $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserLog entity on behalf of system user.
     * @param UserLog $entity
     */
    public function deleteWithSystemModifier(UserLog $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
