<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotFieldConfig;

use LotFieldConfig;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotFieldConfigWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotFieldConfig entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotFieldConfig $entity
     */
    public function save(LotFieldConfig $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotFieldConfig entity in DB on behalf of respective modifier user.
     * @param LotFieldConfig $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotFieldConfig $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotFieldConfig entity in DB on behalf of system user.
     * @param LotFieldConfig $entity
     */
    public function saveWithSystemModifier(LotFieldConfig $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotFieldConfig entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotFieldConfig $entity
     */
    public function forceSave(LotFieldConfig $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotFieldConfig entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotFieldConfig $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotFieldConfig $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotFieldConfig entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotFieldConfig $entity
     */
    public function delete(LotFieldConfig $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotFieldConfig entity on behalf of respective modifier user.
     * @param LotFieldConfig $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotFieldConfig $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotFieldConfig entity on behalf of system user.
     * @param LotFieldConfig $entity
     */
    public function deleteWithSystemModifier(LotFieldConfig $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
