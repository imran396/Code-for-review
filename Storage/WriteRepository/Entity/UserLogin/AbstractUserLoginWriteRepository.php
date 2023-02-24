<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserLogin;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserLogin;

abstract class AbstractUserLoginWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserLogin entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserLogin $entity
     */
    public function save(UserLogin $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserLogin entity in DB on behalf of respective modifier user.
     * @param UserLogin $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserLogin $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserLogin entity in DB on behalf of system user.
     * @param UserLogin $entity
     */
    public function saveWithSystemModifier(UserLogin $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserLogin entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserLogin $entity
     */
    public function forceSave(UserLogin $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserLogin entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserLogin $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserLogin $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserLogin entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserLogin $entity
     */
    public function delete(UserLogin $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserLogin entity on behalf of respective modifier user.
     * @param UserLogin $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserLogin $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserLogin entity on behalf of system user.
     * @param UserLogin $entity
     */
    public function deleteWithSystemModifier(UserLogin $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
