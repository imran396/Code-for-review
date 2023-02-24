<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingAuthorizeNet;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingBillingAuthorizeNet;

abstract class AbstractSettingBillingAuthorizeNetWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingBillingAuthorizeNet entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingBillingAuthorizeNet $entity
     */
    public function save(SettingBillingAuthorizeNet $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingBillingAuthorizeNet entity in DB on behalf of respective modifier user.
     * @param SettingBillingAuthorizeNet $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingBillingAuthorizeNet $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingBillingAuthorizeNet entity in DB on behalf of system user.
     * @param SettingBillingAuthorizeNet $entity
     */
    public function saveWithSystemModifier(SettingBillingAuthorizeNet $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingBillingAuthorizeNet entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingAuthorizeNet $entity
     */
    public function forceSave(SettingBillingAuthorizeNet $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingBillingAuthorizeNet entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingBillingAuthorizeNet $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingBillingAuthorizeNet $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingAuthorizeNet entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingBillingAuthorizeNet $entity
     */
    public function delete(SettingBillingAuthorizeNet $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingBillingAuthorizeNet entity on behalf of respective modifier user.
     * @param SettingBillingAuthorizeNet $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingBillingAuthorizeNet $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingBillingAuthorizeNet entity on behalf of system user.
     * @param SettingBillingAuthorizeNet $entity
     */
    public function deleteWithSystemModifier(SettingBillingAuthorizeNet $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
