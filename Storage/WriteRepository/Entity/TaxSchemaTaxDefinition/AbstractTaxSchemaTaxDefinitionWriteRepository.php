<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxSchemaTaxDefinition;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use TaxSchemaTaxDefinition;

abstract class AbstractTaxSchemaTaxDefinitionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist TaxSchemaTaxDefinition entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param TaxSchemaTaxDefinition $entity
     */
    public function save(TaxSchemaTaxDefinition $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist TaxSchemaTaxDefinition entity in DB on behalf of respective modifier user.
     * @param TaxSchemaTaxDefinition $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(TaxSchemaTaxDefinition $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist TaxSchemaTaxDefinition entity in DB on behalf of system user.
     * @param TaxSchemaTaxDefinition $entity
     */
    public function saveWithSystemModifier(TaxSchemaTaxDefinition $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist TaxSchemaTaxDefinition entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxSchemaTaxDefinition $entity
     */
    public function forceSave(TaxSchemaTaxDefinition $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist TaxSchemaTaxDefinition entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TaxSchemaTaxDefinition $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(TaxSchemaTaxDefinition $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxSchemaTaxDefinition entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param TaxSchemaTaxDefinition $entity
     */
    public function delete(TaxSchemaTaxDefinition $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete TaxSchemaTaxDefinition entity on behalf of respective modifier user.
     * @param TaxSchemaTaxDefinition $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(TaxSchemaTaxDefinition $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TaxSchemaTaxDefinition entity on behalf of system user.
     * @param TaxSchemaTaxDefinition $entity
     */
    public function deleteWithSystemModifier(TaxSchemaTaxDefinition $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
