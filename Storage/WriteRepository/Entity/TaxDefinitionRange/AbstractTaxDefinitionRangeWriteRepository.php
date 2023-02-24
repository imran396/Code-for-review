<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxDefinitionRange;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use TaxDefinitionRange;

abstract class AbstractTaxDefinitionRangeWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist TaxDefinitionRange entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param TaxDefinitionRange $entity
     */
    public function save(TaxDefinitionRange $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist TaxDefinitionRange entity in DB on behalf of respective modifier user.
     * @param TaxDefinitionRange $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(TaxDefinitionRange $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist TaxDefinitionRange entity in DB on behalf of system user.
     * @param TaxDefinitionRange $entity
     */
    public function saveWithSystemModifier(TaxDefinitionRange $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist TaxDefinitionRange entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxDefinitionRange $entity
     */
    public function forceSave(TaxDefinitionRange $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist TaxDefinitionRange entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxDefinitionRange $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(TaxDefinitionRange $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxDefinitionRange entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param TaxDefinitionRange $entity
     */
    public function delete(TaxDefinitionRange $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete TaxDefinitionRange entity on behalf of respective modifier user.
     * @param TaxDefinitionRange $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(TaxDefinitionRange $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxDefinitionRange entity on behalf of system user.
     * @param TaxDefinitionRange $entity
     */
    public function deleteWithSystemModifier(TaxDefinitionRange $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
