<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LocationTaxSchema;

use LocationTaxSchema;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLocationTaxSchemaWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LocationTaxSchema entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LocationTaxSchema $entity
     */
    public function save(LocationTaxSchema $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LocationTaxSchema entity in DB on behalf of respective modifier user.
     * @param LocationTaxSchema $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LocationTaxSchema $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LocationTaxSchema entity in DB on behalf of system user.
     * @param LocationTaxSchema $entity
     */
    public function saveWithSystemModifier(LocationTaxSchema $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LocationTaxSchema entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LocationTaxSchema $entity
     */
    public function forceSave(LocationTaxSchema $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LocationTaxSchema entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LocationTaxSchema $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LocationTaxSchema $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LocationTaxSchema entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LocationTaxSchema $entity
     */
    public function delete(LocationTaxSchema $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LocationTaxSchema entity on behalf of respective modifier user.
     * @param LocationTaxSchema $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LocationTaxSchema $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LocationTaxSchema entity on behalf of system user.
     * @param LocationTaxSchema $entity
     */
    public function deleteWithSystemModifier(LocationTaxSchema $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
