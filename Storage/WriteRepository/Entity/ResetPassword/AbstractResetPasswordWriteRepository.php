<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ResetPassword;

use ResetPassword;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractResetPasswordWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist ResetPassword entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param ResetPassword $entity
     */
    public function save(ResetPassword $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist ResetPassword entity in DB on behalf of respective modifier user.
     * @param ResetPassword $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(ResetPassword $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist ResetPassword entity in DB on behalf of system user.
     * @param ResetPassword $entity
     */
    public function saveWithSystemModifier(ResetPassword $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist ResetPassword entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ResetPassword $entity
     */
    public function forceSave(ResetPassword $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist ResetPassword entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ResetPassword $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(ResetPassword $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ResetPassword entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param ResetPassword $entity
     */
    public function delete(ResetPassword $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete ResetPassword entity on behalf of respective modifier user.
     * @param ResetPassword $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(ResetPassword $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ResetPassword entity on behalf of system user.
     * @param ResetPassword $entity
     */
    public function deleteWithSystemModifier(ResetPassword $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
