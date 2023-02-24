<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingOpayo;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingBillingOpayo;

abstract class AbstractSettingBillingOpayoWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingBillingOpayo entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingBillingOpayo $entity
     */
    public function save(SettingBillingOpayo $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingBillingOpayo entity in DB on behalf of respective modifier user.
     * @param SettingBillingOpayo $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingBillingOpayo $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingBillingOpayo entity in DB on behalf of system user.
     * @param SettingBillingOpayo $entity
     */
    public function saveWithSystemModifier(SettingBillingOpayo $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingBillingOpayo entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingOpayo $entity
     */
    public function forceSave(SettingBillingOpayo $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingBillingOpayo entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingOpayo $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingBillingOpayo $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingOpayo entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingBillingOpayo $entity
     */
    public function delete(SettingBillingOpayo $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingBillingOpayo entity on behalf of respective modifier user.
     * @param SettingBillingOpayo $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingBillingOpayo $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingOpayo entity on behalf of system user.
     * @param SettingBillingOpayo $entity
     */
    public function deleteWithSystemModifier(SettingBillingOpayo $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
