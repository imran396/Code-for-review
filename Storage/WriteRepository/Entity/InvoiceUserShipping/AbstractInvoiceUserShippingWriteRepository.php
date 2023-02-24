<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceUserShipping;

use InvoiceUserShipping;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoiceUserShippingWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist InvoiceUserShipping entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param InvoiceUserShipping $entity
     */
    public function save(InvoiceUserShipping $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist InvoiceUserShipping entity in DB on behalf of respective modifier user.
     * @param InvoiceUserShipping $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(InvoiceUserShipping $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist InvoiceUserShipping entity in DB on behalf of system user.
     * @param InvoiceUserShipping $entity
     */
    public function saveWithSystemModifier(InvoiceUserShipping $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist InvoiceUserShipping entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceUserShipping $entity
     */
    public function forceSave(InvoiceUserShipping $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist InvoiceUserShipping entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceUserShipping $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(InvoiceUserShipping $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceUserShipping entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param InvoiceUserShipping $entity
     */
    public function delete(InvoiceUserShipping $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete InvoiceUserShipping entity on behalf of respective modifier user.
     * @param InvoiceUserShipping $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(InvoiceUserShipping $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceUserShipping entity on behalf of system user.
     * @param InvoiceUserShipping $entity
     */
    public function deleteWithSystemModifier(InvoiceUserShipping $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
