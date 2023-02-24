<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemCategory;

use LotItemCategory;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotItemCategoryWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotItemCategory entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotItemCategory $entity
     */
    public function save(LotItemCategory $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotItemCategory entity in DB on behalf of respective modifier user.
     * @param LotItemCategory $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotItemCategory $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotItemCategory entity in DB on behalf of system user.
     * @param LotItemCategory $entity
     */
    public function saveWithSystemModifier(LotItemCategory $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotItemCategory entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemCategory $entity
     */
    public function forceSave(LotItemCategory $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotItemCategory entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemCategory $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotItemCategory $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemCategory entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotItemCategory $entity
     */
    public function delete(LotItemCategory $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotItemCategory entity on behalf of respective modifier user.
     * @param LotItemCategory $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotItemCategory $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemCategory entity on behalf of system user.
     * @param LotItemCategory $entity
     */
    public function deleteWithSystemModifier(LotItemCategory $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
