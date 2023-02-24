<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotCategoryCustData;

use LotCategoryCustData;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotCategoryCustDataWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotCategoryCustData entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotCategoryCustData $entity
     */
    public function save(LotCategoryCustData $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotCategoryCustData entity in DB on behalf of respective modifier user.
     * @param LotCategoryCustData $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotCategoryCustData $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotCategoryCustData entity in DB on behalf of system user.
     * @param LotCategoryCustData $entity
     */
    public function saveWithSystemModifier(LotCategoryCustData $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotCategoryCustData entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotCategoryCustData $entity
     */
    public function forceSave(LotCategoryCustData $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotCategoryCustData entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotCategoryCustData $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotCategoryCustData $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotCategoryCustData entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotCategoryCustData $entity
     */
    public function delete(LotCategoryCustData $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotCategoryCustData entity on behalf of respective modifier user.
     * @param LotCategoryCustData $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotCategoryCustData $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotCategoryCustData entity on behalf of system user.
     * @param LotCategoryCustData $entity
     */
    public function deleteWithSystemModifier(LotCategoryCustData $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
