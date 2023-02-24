<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotCategoryBuyerGroup;

use LotCategoryBuyerGroup;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotCategoryBuyerGroupWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotCategoryBuyerGroup entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotCategoryBuyerGroup $entity
     */
    public function save(LotCategoryBuyerGroup $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotCategoryBuyerGroup entity in DB on behalf of respective modifier user.
     * @param LotCategoryBuyerGroup $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotCategoryBuyerGroup $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotCategoryBuyerGroup entity in DB on behalf of system user.
     * @param LotCategoryBuyerGroup $entity
     */
    public function saveWithSystemModifier(LotCategoryBuyerGroup $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotCategoryBuyerGroup entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotCategoryBuyerGroup $entity
     */
    public function forceSave(LotCategoryBuyerGroup $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotCategoryBuyerGroup entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotCategoryBuyerGroup $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotCategoryBuyerGroup $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotCategoryBuyerGroup entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotCategoryBuyerGroup $entity
     */
    public function delete(LotCategoryBuyerGroup $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotCategoryBuyerGroup entity on behalf of respective modifier user.
     * @param LotCategoryBuyerGroup $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotCategoryBuyerGroup $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotCategoryBuyerGroup entity on behalf of system user.
     * @param LotCategoryBuyerGroup $entity
     */
    public function deleteWithSystemModifier(LotCategoryBuyerGroup $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
