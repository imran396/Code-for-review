<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceAuction;

use InvoiceAuction;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoiceAuctionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist InvoiceAuction entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param InvoiceAuction $entity
     */
    public function save(InvoiceAuction $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist InvoiceAuction entity in DB on behalf of respective modifier user.
     * @param InvoiceAuction $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(InvoiceAuction $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist InvoiceAuction entity in DB on behalf of system user.
     * @param InvoiceAuction $entity
     */
    public function saveWithSystemModifier(InvoiceAuction $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist InvoiceAuction entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceAuction $entity
     */
    public function forceSave(InvoiceAuction $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist InvoiceAuction entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceAuction $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(InvoiceAuction $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceAuction entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param InvoiceAuction $entity
     */
    public function delete(InvoiceAuction $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete InvoiceAuction entity on behalf of respective modifier user.
     * @param InvoiceAuction $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(InvoiceAuction $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceAuction entity on behalf of system user.
     * @param InvoiceAuction $entity
     */
    public function deleteWithSystemModifier(InvoiceAuction $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
