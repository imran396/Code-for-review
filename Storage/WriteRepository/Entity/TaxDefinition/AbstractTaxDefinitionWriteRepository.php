<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxDefinition;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use TaxDefinition;

abstract class AbstractTaxDefinitionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist TaxDefinition entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param TaxDefinition $entity
     */
    public function save(TaxDefinition $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist TaxDefinition entity in DB on behalf of respective modifier user.
     * @param TaxDefinition $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(TaxDefinition $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist TaxDefinition entity in DB on behalf of system user.
     * @param TaxDefinition $entity
     */
    public function saveWithSystemModifier(TaxDefinition $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist TaxDefinition entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxDefinition $entity
     */
    public function forceSave(TaxDefinition $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist TaxDefinition entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxDefinition $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(TaxDefinition $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxDefinition entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param TaxDefinition $entity
     */
    public function delete(TaxDefinition $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete TaxDefinition entity on behalf of respective modifier user.
     * @param TaxDefinition $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(TaxDefinition $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxDefinition entity on behalf of system user.
     * @param TaxDefinition $entity
     */
    public function deleteWithSystemModifier(TaxDefinition $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
