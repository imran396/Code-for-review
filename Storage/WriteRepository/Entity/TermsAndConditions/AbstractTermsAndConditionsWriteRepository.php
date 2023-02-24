<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TermsAndConditions;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use TermsAndConditions;

abstract class AbstractTermsAndConditionsWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist TermsAndConditions entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param TermsAndConditions $entity
     */
    public function save(TermsAndConditions $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist TermsAndConditions entity in DB on behalf of respective modifier user.
     * @param TermsAndConditions $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(TermsAndConditions $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist TermsAndConditions entity in DB on behalf of system user.
     * @param TermsAndConditions $entity
     */
    public function saveWithSystemModifier(TermsAndConditions $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist TermsAndConditions entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TermsAndConditions $entity
     */
    public function forceSave(TermsAndConditions $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist TermsAndConditions entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TermsAndConditions $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(TermsAndConditions $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TermsAndConditions entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param TermsAndConditions $entity
     */
    public function delete(TermsAndConditions $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete TermsAndConditions entity on behalf of respective modifier user.
     * @param TermsAndConditions $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(TermsAndConditions $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TermsAndConditions entity on behalf of system user.
     * @param TermsAndConditions $entity
     */
    public function deleteWithSystemModifier(TermsAndConditions $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
