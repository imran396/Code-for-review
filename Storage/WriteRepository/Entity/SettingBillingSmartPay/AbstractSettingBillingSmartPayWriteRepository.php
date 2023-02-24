<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingSmartPay;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingBillingSmartPay;

abstract class AbstractSettingBillingSmartPayWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingBillingSmartPay entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingBillingSmartPay $entity
     */
    public function save(SettingBillingSmartPay $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingBillingSmartPay entity in DB on behalf of respective modifier user.
     * @param SettingBillingSmartPay $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingBillingSmartPay $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingBillingSmartPay entity in DB on behalf of system user.
     * @param SettingBillingSmartPay $entity
     */
    public function saveWithSystemModifier(SettingBillingSmartPay $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingBillingSmartPay entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingSmartPay $entity
     */
    public function forceSave(SettingBillingSmartPay $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingBillingSmartPay entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingSmartPay $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingBillingSmartPay $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingSmartPay entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingBillingSmartPay $entity
     */
    public function delete(SettingBillingSmartPay $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingBillingSmartPay entity on behalf of respective modifier user.
     * @param SettingBillingSmartPay $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingBillingSmartPay $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingSmartPay entity on behalf of system user.
     * @param SettingBillingSmartPay $entity
     */
    public function deleteWithSystemModifier(SettingBillingSmartPay $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
