<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxSchema;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use TaxSchema;

abstract class AbstractTaxSchemaWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist TaxSchema entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param TaxSchema $entity
     */
    public function save(TaxSchema $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist TaxSchema entity in DB on behalf of respective modifier user.
     * @param TaxSchema $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(TaxSchema $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist TaxSchema entity in DB on behalf of system user.
     * @param TaxSchema $entity
     */
    public function saveWithSystemModifier(TaxSchema $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist TaxSchema entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxSchema $entity
     */
    public function forceSave(TaxSchema $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist TaxSchema entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxSchema $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(TaxSchema $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxSchema entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param TaxSchema $entity
     */
    public function delete(TaxSchema $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete TaxSchema entity on behalf of respective modifier user.
     * @param TaxSchema $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(TaxSchema $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxSchema entity on behalf of system user.
     * @param TaxSchema $entity
     */
    public function deleteWithSystemModifier(TaxSchema $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
