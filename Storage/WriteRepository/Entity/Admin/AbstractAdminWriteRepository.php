<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Admin;

use Admin;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAdminWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Admin entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Admin $entity
     */
    public function save(Admin $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Admin entity in DB on behalf of respective modifier user.
     * @param Admin $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Admin $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Admin entity in DB on behalf of system user.
     * @param Admin $entity
     */
    public function saveWithSystemModifier(Admin $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Admin entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Admin $entity
     */
    public function forceSave(Admin $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Admin entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Admin $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Admin $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Admin entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Admin $entity
     */
    public function delete(Admin $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Admin entity on behalf of respective modifier user.
     * @param Admin $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Admin $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Admin entity on behalf of system user.
     * @param Admin $entity
     */
    public function deleteWithSystemModifier(Admin $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
