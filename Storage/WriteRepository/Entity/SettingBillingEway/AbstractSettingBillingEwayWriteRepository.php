<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingEway;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingBillingEway;

abstract class AbstractSettingBillingEwayWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingBillingEway entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingBillingEway $entity
     */
    public function save(SettingBillingEway $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingBillingEway entity in DB on behalf of respective modifier user.
     * @param SettingBillingEway $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingBillingEway $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingBillingEway entity in DB on behalf of system user.
     * @param SettingBillingEway $entity
     */
    public function saveWithSystemModifier(SettingBillingEway $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingBillingEway entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingEway $entity
     */
    public function forceSave(SettingBillingEway $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingBillingEway entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingEway $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingBillingEway $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingEway entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingBillingEway $entity
     */
    public function delete(SettingBillingEway $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingBillingEway entity on behalf of respective modifier user.
     * @param SettingBillingEway $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingBillingEway $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingEway entity on behalf of system user.
     * @param SettingBillingEway $entity
     */
    public function deleteWithSystemModifier(SettingBillingEway $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
