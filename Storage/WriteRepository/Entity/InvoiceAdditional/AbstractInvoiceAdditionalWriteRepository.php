<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceAdditional;

use InvoiceAdditional;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoiceAdditionalWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist InvoiceAdditional entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param InvoiceAdditional $entity
     */
    public function save(InvoiceAdditional $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist InvoiceAdditional entity in DB on behalf of respective modifier user.
     * @param InvoiceAdditional $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(InvoiceAdditional $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist InvoiceAdditional entity in DB on behalf of system user.
     * @param InvoiceAdditional $entity
     */
    public function saveWithSystemModifier(InvoiceAdditional $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist InvoiceAdditional entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceAdditional $entity
     */
    public function forceSave(InvoiceAdditional $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist InvoiceAdditional entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceAdditional $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(InvoiceAdditional $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceAdditional entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param InvoiceAdditional $entity
     */
    public function delete(InvoiceAdditional $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete InvoiceAdditional entity on behalf of respective modifier user.
     * @param InvoiceAdditional $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(InvoiceAdditional $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceAdditional entity on behalf of system user.
     * @param InvoiceAdditional $entity
     */
    public function deleteWithSystemModifier(InvoiceAdditional $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
