<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Invoice;

use Invoice;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoiceWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Invoice entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Invoice $entity
     */
    public function save(Invoice $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Invoice entity in DB on behalf of respective modifier user.
     * @param Invoice $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Invoice $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Invoice entity in DB on behalf of system user.
     * @param Invoice $entity
     */
    public function saveWithSystemModifier(Invoice $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Invoice entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Invoice $entity
     */
    public function forceSave(Invoice $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Invoice entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Invoice $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Invoice $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Invoice entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Invoice $entity
     */
    public function delete(Invoice $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Invoice entity on behalf of respective modifier user.
     * @param Invoice $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Invoice $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Invoice entity on behalf of system user.
     * @param Invoice $entity
     */
    public function deleteWithSystemModifier(Invoice $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
