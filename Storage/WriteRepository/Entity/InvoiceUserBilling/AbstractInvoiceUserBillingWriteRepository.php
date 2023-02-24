<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceUserBilling;

use InvoiceUserBilling;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoiceUserBillingWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist InvoiceUserBilling entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param InvoiceUserBilling $entity
     */
    public function save(InvoiceUserBilling $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist InvoiceUserBilling entity in DB on behalf of respective modifier user.
     * @param InvoiceUserBilling $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(InvoiceUserBilling $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist InvoiceUserBilling entity in DB on behalf of system user.
     * @param InvoiceUserBilling $entity
     */
    public function saveWithSystemModifier(InvoiceUserBilling $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist InvoiceUserBilling entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceUserBilling $entity
     */
    public function forceSave(InvoiceUserBilling $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist InvoiceUserBilling entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceUserBilling $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(InvoiceUserBilling $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceUserBilling entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param InvoiceUserBilling $entity
     */
    public function delete(InvoiceUserBilling $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete InvoiceUserBilling entity on behalf of respective modifier user.
     * @param InvoiceUserBilling $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(InvoiceUserBilling $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceUserBilling entity on behalf of system user.
     * @param InvoiceUserBilling $entity
     */
    public function deleteWithSystemModifier(InvoiceUserBilling $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
