<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingNmi;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingBillingNmi;

abstract class AbstractSettingBillingNmiWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingBillingNmi entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingBillingNmi $entity
     */
    public function save(SettingBillingNmi $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingBillingNmi entity in DB on behalf of respective modifier user.
     * @param SettingBillingNmi $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingBillingNmi $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingBillingNmi entity in DB on behalf of system user.
     * @param SettingBillingNmi $entity
     */
    public function saveWithSystemModifier(SettingBillingNmi $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingBillingNmi entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingNmi $entity
     */
    public function forceSave(SettingBillingNmi $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingBillingNmi entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingNmi $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingBillingNmi $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingNmi entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingBillingNmi $entity
     */
    public function delete(SettingBillingNmi $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingBillingNmi entity on behalf of respective modifier user.
     * @param SettingBillingNmi $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingBillingNmi $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingNmi entity on behalf of system user.
     * @param SettingBillingNmi $entity
     */
    public function deleteWithSystemModifier(SettingBillingNmi $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
