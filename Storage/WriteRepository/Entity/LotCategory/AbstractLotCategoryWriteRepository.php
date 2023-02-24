<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotCategory;

use LotCategory;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotCategoryWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotCategory entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotCategory $entity
     */
    public function save(LotCategory $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotCategory entity in DB on behalf of respective modifier user.
     * @param LotCategory $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotCategory $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotCategory entity in DB on behalf of system user.
     * @param LotCategory $entity
     */
    public function saveWithSystemModifier(LotCategory $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotCategory entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotCategory $entity
     */
    public function forceSave(LotCategory $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotCategory entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotCategory $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotCategory $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotCategory entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotCategory $entity
     */
    public function delete(LotCategory $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotCategory entity on behalf of respective modifier user.
     * @param LotCategory $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotCategory $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotCategory entity on behalf of system user.
     * @param LotCategory $entity
     */
    public function deleteWithSystemModifier(LotCategory $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
