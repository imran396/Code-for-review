<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MySearchCategory;

use MySearchCategory;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractMySearchCategoryWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist MySearchCategory entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param MySearchCategory $entity
     */
    public function save(MySearchCategory $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist MySearchCategory entity in DB on behalf of respective modifier user.
     * @param MySearchCategory $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(MySearchCategory $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist MySearchCategory entity in DB on behalf of system user.
     * @param MySearchCategory $entity
     */
    public function saveWithSystemModifier(MySearchCategory $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist MySearchCategory entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MySearchCategory $entity
     */
    public function forceSave(MySearchCategory $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist MySearchCategory entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MySearchCategory $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(MySearchCategory $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MySearchCategory entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param MySearchCategory $entity
     */
    public function delete(MySearchCategory $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete MySearchCategory entity on behalf of respective modifier user.
     * @param MySearchCategory $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(MySearchCategory $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MySearchCategory entity on behalf of system user.
     * @param MySearchCategory $entity
     */
    public function deleteWithSystemModifier(MySearchCategory $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
