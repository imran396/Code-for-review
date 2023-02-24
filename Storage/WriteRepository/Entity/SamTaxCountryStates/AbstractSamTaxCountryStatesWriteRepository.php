<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SamTaxCountryStates;

use SamTaxCountryStates;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractSamTaxCountryStatesWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SamTaxCountryStates entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SamTaxCountryStates $entity
     */
    public function save(SamTaxCountryStates $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SamTaxCountryStates entity in DB on behalf of respective modifier user.
     * @param SamTaxCountryStates $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SamTaxCountryStates $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SamTaxCountryStates entity in DB on behalf of system user.
     * @param SamTaxCountryStates $entity
     */
    public function saveWithSystemModifier(SamTaxCountryStates $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SamTaxCountryStates entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SamTaxCountryStates $entity
     */
    public function forceSave(SamTaxCountryStates $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SamTaxCountryStates entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SamTaxCountryStates $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SamTaxCountryStates $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SamTaxCountryStates entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SamTaxCountryStates $entity
     */
    public function delete(SamTaxCountryStates $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SamTaxCountryStates entity on behalf of respective modifier user.
     * @param SamTaxCountryStates $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SamTaxCountryStates $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SamTaxCountryStates entity on behalf of system user.
     * @param SamTaxCountryStates $entity
     */
    public function deleteWithSystemModifier(SamTaxCountryStates $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
