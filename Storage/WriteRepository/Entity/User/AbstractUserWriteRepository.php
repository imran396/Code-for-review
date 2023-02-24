<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\User;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use User;

abstract class AbstractUserWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist User entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param User $entity
     */
    public function save(User $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist User entity in DB on behalf of respective modifier user.
     * @param User $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(User $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist User entity in DB on behalf of system user.
     * @param User $entity
     */
    public function saveWithSystemModifier(User $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist User entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param User $entity
     */
    public function forceSave(User $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist User entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param User $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(User $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete User entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param User $entity
     */
    public function delete(User $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete User entity on behalf of respective modifier user.
     * @param User $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(User $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete User entity on behalf of system user.
     * @param User $entity
     */
    public function deleteWithSystemModifier(User $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
