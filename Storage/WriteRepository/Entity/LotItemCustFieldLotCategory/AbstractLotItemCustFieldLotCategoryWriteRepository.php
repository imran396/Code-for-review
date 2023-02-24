<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemCustFieldLotCategory;

use LotItemCustFieldLotCategory;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotItemCustFieldLotCategoryWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotItemCustFieldLotCategory entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotItemCustFieldLotCategory $entity
     */
    public function save(LotItemCustFieldLotCategory $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotItemCustFieldLotCategory entity in DB on behalf of respective modifier user.
     * @param LotItemCustFieldLotCategory $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotItemCustFieldLotCategory $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotItemCustFieldLotCategory entity in DB on behalf of system user.
     * @param LotItemCustFieldLotCategory $entity
     */
    public function saveWithSystemModifier(LotItemCustFieldLotCategory $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotItemCustFieldLotCategory entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemCustFieldLotCategory $entity
     */
    public function forceSave(LotItemCustFieldLotCategory $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotItemCustFieldLotCategory entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemCustFieldLotCategory $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotItemCustFieldLotCategory $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemCustFieldLotCategory entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotItemCustFieldLotCategory $entity
     */
    public function delete(LotItemCustFieldLotCategory $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotItemCustFieldLotCategory entity on behalf of respective modifier user.
     * @param LotItemCustFieldLotCategory $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotItemCustFieldLotCategory $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemCustFieldLotCategory entity on behalf of system user.
     * @param LotItemCustFieldLotCategory $entity
     */
    public function deleteWithSystemModifier(LotItemCustFieldLotCategory $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
