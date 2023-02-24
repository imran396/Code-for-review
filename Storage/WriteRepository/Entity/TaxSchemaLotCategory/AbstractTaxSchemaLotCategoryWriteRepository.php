<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxSchemaLotCategory;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use TaxSchemaLotCategory;

abstract class AbstractTaxSchemaLotCategoryWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist TaxSchemaLotCategory entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param TaxSchemaLotCategory $entity
     */
    public function save(TaxSchemaLotCategory $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist TaxSchemaLotCategory entity in DB on behalf of respective modifier user.
     * @param TaxSchemaLotCategory $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(TaxSchemaLotCategory $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist TaxSchemaLotCategory entity in DB on behalf of system user.
     * @param TaxSchemaLotCategory $entity
     */
    public function saveWithSystemModifier(TaxSchemaLotCategory $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist TaxSchemaLotCategory entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxSchemaLotCategory $entity
     */
    public function forceSave(TaxSchemaLotCategory $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist TaxSchemaLotCategory entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxSchemaLotCategory $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(TaxSchemaLotCategory $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxSchemaLotCategory entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param TaxSchemaLotCategory $entity
     */
    public function delete(TaxSchemaLotCategory $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete TaxSchemaLotCategory entity on behalf of respective modifier user.
     * @param TaxSchemaLotCategory $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(TaxSchemaLotCategory $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxSchemaLotCategory entity on behalf of system user.
     * @param TaxSchemaLotCategory $entity
     */
    public function deleteWithSystemModifier(TaxSchemaLotCategory $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
