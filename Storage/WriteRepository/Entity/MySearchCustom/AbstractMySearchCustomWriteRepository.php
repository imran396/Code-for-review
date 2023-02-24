<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MySearchCustom;

use MySearchCustom;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractMySearchCustomWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist MySearchCustom entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param MySearchCustom $entity
     */
    public function save(MySearchCustom $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist MySearchCustom entity in DB on behalf of respective modifier user.
     * @param MySearchCustom $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(MySearchCustom $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist MySearchCustom entity in DB on behalf of system user.
     * @param MySearchCustom $entity
     */
    public function saveWithSystemModifier(MySearchCustom $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist MySearchCustom entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MySearchCustom $entity
     */
    public function forceSave(MySearchCustom $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist MySearchCustom entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MySearchCustom $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(MySearchCustom $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MySearchCustom entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param MySearchCustom $entity
     */
    public function delete(MySearchCustom $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete MySearchCustom entity on behalf of respective modifier user.
     * @param MySearchCustom $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(MySearchCustom $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MySearchCustom entity on behalf of system user.
     * @param MySearchCustom $entity
     */
    public function deleteWithSystemModifier(MySearchCustom $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
