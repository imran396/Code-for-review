<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingPaypal;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingBillingPaypal;

abstract class AbstractSettingBillingPaypalWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingBillingPaypal entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingBillingPaypal $entity
     */
    public function save(SettingBillingPaypal $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingBillingPaypal entity in DB on behalf of respective modifier user.
     * @param SettingBillingPaypal $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingBillingPaypal $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingBillingPaypal entity in DB on behalf of system user.
     * @param SettingBillingPaypal $entity
     */
    public function saveWithSystemModifier(SettingBillingPaypal $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingBillingPaypal entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingPaypal $entity
     */
    public function forceSave(SettingBillingPaypal $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingBillingPaypal entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingPaypal $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingBillingPaypal $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingPaypal entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingBillingPaypal $entity
     */
    public function delete(SettingBillingPaypal $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingBillingPaypal entity on behalf of respective modifier user.
     * @param SettingBillingPaypal $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingBillingPaypal $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingPaypal entity on behalf of system user.
     * @param SettingBillingPaypal $entity
     */
    public function deleteWithSystemModifier(SettingBillingPaypal $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
