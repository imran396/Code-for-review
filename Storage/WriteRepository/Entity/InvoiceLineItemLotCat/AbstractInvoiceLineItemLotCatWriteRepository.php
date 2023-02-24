<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceLineItemLotCat;

use InvoiceLineItemLotCat;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoiceLineItemLotCatWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist InvoiceLineItemLotCat entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param InvoiceLineItemLotCat $entity
     */
    public function save(InvoiceLineItemLotCat $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist InvoiceLineItemLotCat entity in DB on behalf of respective modifier user.
     * @param InvoiceLineItemLotCat $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(InvoiceLineItemLotCat $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist InvoiceLineItemLotCat entity in DB on behalf of system user.
     * @param InvoiceLineItemLotCat $entity
     */
    public function saveWithSystemModifier(InvoiceLineItemLotCat $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist InvoiceLineItemLotCat entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceLineItemLotCat $entity
     */
    public function forceSave(InvoiceLineItemLotCat $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist InvoiceLineItemLotCat entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceLineItemLotCat $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(InvoiceLineItemLotCat $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceLineItemLotCat entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param InvoiceLineItemLotCat $entity
     */
    public function delete(InvoiceLineItemLotCat $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete InvoiceLineItemLotCat entity on behalf of respective modifier user.
     * @param InvoiceLineItemLotCat $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(InvoiceLineItemLotCat $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceLineItemLotCat entity on behalf of system user.
     * @param InvoiceLineItemLotCat $entity
     */
    public function deleteWithSystemModifier(InvoiceLineItemLotCat $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
