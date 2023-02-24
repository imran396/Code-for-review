<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemCustField;

use LotItemCustField;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotItemCustFieldWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotItemCustField entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotItemCustField $entity
     */
    public function save(LotItemCustField $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotItemCustField entity in DB on behalf of respective modifier user.
     * @param LotItemCustField $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotItemCustField $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotItemCustField entity in DB on behalf of system user.
     * @param LotItemCustField $entity
     */
    public function saveWithSystemModifier(LotItemCustField $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotItemCustField entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemCustField $entity
     */
    public function forceSave(LotItemCustField $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotItemCustField entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemCustField $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotItemCustField $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemCustField entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotItemCustField $entity
     */
    public function delete(LotItemCustField $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotItemCustField entity on behalf of respective modifier user.
     * @param LotItemCustField $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotItemCustField $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemCustField entity on behalf of system user.
     * @param LotItemCustField $entity
     */
    public function deleteWithSystemModifier(LotItemCustField $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
