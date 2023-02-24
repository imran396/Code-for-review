<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceLineItem;

use InvoiceLineItem;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoiceLineItemWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist InvoiceLineItem entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param InvoiceLineItem $entity
     */
    public function save(InvoiceLineItem $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist InvoiceLineItem entity in DB on behalf of respective modifier user.
     * @param InvoiceLineItem $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(InvoiceLineItem $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist InvoiceLineItem entity in DB on behalf of system user.
     * @param InvoiceLineItem $entity
     */
    public function saveWithSystemModifier(InvoiceLineItem $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist InvoiceLineItem entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceLineItem $entity
     */
    public function forceSave(InvoiceLineItem $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist InvoiceLineItem entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceLineItem $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(InvoiceLineItem $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceLineItem entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param InvoiceLineItem $entity
     */
    public function delete(InvoiceLineItem $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete InvoiceLineItem entity on behalf of respective modifier user.
     * @param InvoiceLineItem $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(InvoiceLineItem $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceLineItem entity on behalf of system user.
     * @param InvoiceLineItem $entity
     */
    public function deleteWithSystemModifier(InvoiceLineItem $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
