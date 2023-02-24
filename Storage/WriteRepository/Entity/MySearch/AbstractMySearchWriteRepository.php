<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MySearch;

use MySearch;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractMySearchWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist MySearch entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param MySearch $entity
     */
    public function save(MySearch $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist MySearch entity in DB on behalf of respective modifier user.
     * @param MySearch $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(MySearch $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist MySearch entity in DB on behalf of system user.
     * @param MySearch $entity
     */
    public function saveWithSystemModifier(MySearch $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist MySearch entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MySearch $entity
     */
    public function forceSave(MySearch $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist MySearch entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MySearch $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(MySearch $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MySearch entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param MySearch $entity
     */
    public function delete(MySearch $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete MySearch entity on behalf of respective modifier user.
     * @param MySearch $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(MySearch $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MySearch entity on behalf of system user.
     * @param MySearch $entity
     */
    public function deleteWithSystemModifier(MySearch $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
