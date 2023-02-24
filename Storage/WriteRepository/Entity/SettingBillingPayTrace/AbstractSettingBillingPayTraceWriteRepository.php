<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingPayTrace;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingBillingPayTrace;

abstract class AbstractSettingBillingPayTraceWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingBillingPayTrace entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingBillingPayTrace $entity
     */
    public function save(SettingBillingPayTrace $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingBillingPayTrace entity in DB on behalf of respective modifier user.
     * @param SettingBillingPayTrace $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingBillingPayTrace $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingBillingPayTrace entity in DB on behalf of system user.
     * @param SettingBillingPayTrace $entity
     */
    public function saveWithSystemModifier(SettingBillingPayTrace $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingBillingPayTrace entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingPayTrace $entity
     */
    public function forceSave(SettingBillingPayTrace $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingBillingPayTrace entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingPayTrace $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingBillingPayTrace $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingPayTrace entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingBillingPayTrace $entity
     */
    public function delete(SettingBillingPayTrace $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingBillingPayTrace entity on behalf of respective modifier user.
     * @param SettingBillingPayTrace $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingBillingPayTrace $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingPayTrace entity on behalf of system user.
     * @param SettingBillingPayTrace $entity
     */
    public function deleteWithSystemModifier(SettingBillingPayTrace $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
