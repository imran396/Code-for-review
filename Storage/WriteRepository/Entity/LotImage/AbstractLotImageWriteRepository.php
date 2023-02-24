<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotImage;

use LotImage;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotImageWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotImage entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotImage $entity
     */
    public function save(LotImage $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotImage entity in DB on behalf of respective modifier user.
     * @param LotImage $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotImage $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotImage entity in DB on behalf of system user.
     * @param LotImage $entity
     */
    public function saveWithSystemModifier(LotImage $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotImage entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotImage $entity
     */
    public function forceSave(LotImage $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotImage entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotImage $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotImage $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotImage entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotImage $entity
     */
    public function delete(LotImage $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotImage entity on behalf of respective modifier user.
     * @param LotImage $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotImage $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotImage entity on behalf of system user.
     * @param LotImage $entity
     */
    public function deleteWithSystemModifier(LotImage $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
