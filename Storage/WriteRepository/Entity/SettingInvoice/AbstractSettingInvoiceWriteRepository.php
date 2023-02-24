<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingInvoice;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingInvoice;

abstract class AbstractSettingInvoiceWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingInvoice entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingInvoice $entity
     */
    public function save(SettingInvoice $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingInvoice entity in DB on behalf of respective modifier user.
     * @param SettingInvoice $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingInvoice $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingInvoice entity in DB on behalf of system user.
     * @param SettingInvoice $entity
     */
    public function saveWithSystemModifier(SettingInvoice $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingInvoice entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingInvoice $entity
     */
    public function forceSave(SettingInvoice $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingInvoice entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingInvoice $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingInvoice $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingInvoice entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingInvoice $entity
     */
    public function delete(SettingInvoice $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingInvoice entity on behalf of respective modifier user.
     * @param SettingInvoice $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingInvoice $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingInvoice entity on behalf of system user.
     * @param SettingInvoice $entity
     */
    public function deleteWithSystemModifier(SettingInvoice $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
