<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemCustData;

use LotItemCustData;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotItemCustDataWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotItemCustData entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotItemCustData $entity
     */
    public function save(LotItemCustData $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotItemCustData entity in DB on behalf of respective modifier user.
     * @param LotItemCustData $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotItemCustData $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotItemCustData entity in DB on behalf of system user.
     * @param LotItemCustData $entity
     */
    public function saveWithSystemModifier(LotItemCustData $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotItemCustData entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemCustData $entity
     */
    public function forceSave(LotItemCustData $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotItemCustData entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemCustData $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotItemCustData $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemCustData entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotItemCustData $entity
     */
    public function delete(LotItemCustData $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotItemCustData entity on behalf of respective modifier user.
     * @param LotItemCustData $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotItemCustData $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemCustData entity on behalf of system user.
     * @param LotItemCustData $entity
     */
    public function deleteWithSystemModifier(LotItemCustData $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
