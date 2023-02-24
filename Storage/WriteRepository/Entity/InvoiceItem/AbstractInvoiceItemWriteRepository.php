<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceItem;

use InvoiceItem;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoiceItemWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist InvoiceItem entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param InvoiceItem $entity
     */
    public function save(InvoiceItem $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist InvoiceItem entity in DB on behalf of respective modifier user.
     * @param InvoiceItem $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(InvoiceItem $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist InvoiceItem entity in DB on behalf of system user.
     * @param InvoiceItem $entity
     */
    public function saveWithSystemModifier(InvoiceItem $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist InvoiceItem entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceItem $entity
     */
    public function forceSave(InvoiceItem $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist InvoiceItem entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceItem $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(InvoiceItem $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceItem entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param InvoiceItem $entity
     */
    public function delete(InvoiceItem $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete InvoiceItem entity on behalf of respective modifier user.
     * @param InvoiceItem $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(InvoiceItem $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceItem entity on behalf of system user.
     * @param InvoiceItem $entity
     */
    public function deleteWithSystemModifier(InvoiceItem $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
