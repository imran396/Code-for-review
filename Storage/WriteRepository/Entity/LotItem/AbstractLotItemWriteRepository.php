<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItem;

use LotItem;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotItemWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotItem entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotItem $entity
     */
    public function save(LotItem $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotItem entity in DB on behalf of respective modifier user.
     * @param LotItem $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotItem $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotItem entity in DB on behalf of system user.
     * @param LotItem $entity
     */
    public function saveWithSystemModifier(LotItem $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotItem entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItem $entity
     */
    public function forceSave(LotItem $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotItem entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItem $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotItem $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItem entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotItem $entity
     */
    public function delete(LotItem $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotItem entity on behalf of respective modifier user.
     * @param LotItem $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotItem $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItem entity on behalf of system user.
     * @param LotItem $entity
     */
    public function deleteWithSystemModifier(LotItem $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
