<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoicePaymentMethod;

use InvoicePaymentMethod;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoicePaymentMethodWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist InvoicePaymentMethod entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param InvoicePaymentMethod $entity
     */
    public function save(InvoicePaymentMethod $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist InvoicePaymentMethod entity in DB on behalf of respective modifier user.
     * @param InvoicePaymentMethod $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(InvoicePaymentMethod $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist InvoicePaymentMethod entity in DB on behalf of system user.
     * @param InvoicePaymentMethod $entity
     */
    public function saveWithSystemModifier(InvoicePaymentMethod $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist InvoicePaymentMethod entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoicePaymentMethod $entity
     */
    public function forceSave(InvoicePaymentMethod $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist InvoicePaymentMethod entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoicePaymentMethod $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(InvoicePaymentMethod $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoicePaymentMethod entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param InvoicePaymentMethod $entity
     */
    public function delete(InvoicePaymentMethod $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete InvoicePaymentMethod entity on behalf of respective modifier user.
     * @param InvoicePaymentMethod $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(InvoicePaymentMethod $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoicePaymentMethod entity on behalf of system user.
     * @param InvoicePaymentMethod $entity
     */
    public function deleteWithSystemModifier(InvoicePaymentMethod $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
