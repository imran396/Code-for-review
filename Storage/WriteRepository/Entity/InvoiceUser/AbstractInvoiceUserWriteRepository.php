<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceUser;

use InvoiceUser;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractInvoiceUserWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist InvoiceUser entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param InvoiceUser $entity
     */
    public function save(InvoiceUser $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist InvoiceUser entity in DB on behalf of respective modifier user.
     * @param InvoiceUser $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(InvoiceUser $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist InvoiceUser entity in DB on behalf of system user.
     * @param InvoiceUser $entity
     */
    public function saveWithSystemModifier(InvoiceUser $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist InvoiceUser entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceUser $entity
     */
    public function forceSave(InvoiceUser $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist InvoiceUser entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param InvoiceUser $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(InvoiceUser $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceUser entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param InvoiceUser $entity
     */
    public function delete(InvoiceUser $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete InvoiceUser entity on behalf of respective modifier user.
     * @param InvoiceUser $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(InvoiceUser $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete InvoiceUser entity on behalf of system user.
     * @param InvoiceUser $entity
     */
    public function deleteWithSystemModifier(InvoiceUser $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
