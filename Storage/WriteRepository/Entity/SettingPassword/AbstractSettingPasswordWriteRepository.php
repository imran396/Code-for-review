<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingPassword;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingPassword;

abstract class AbstractSettingPasswordWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingPassword entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingPassword $entity
     */
    public function save(SettingPassword $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingPassword entity in DB on behalf of respective modifier user.
     * @param SettingPassword $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingPassword $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingPassword entity in DB on behalf of system user.
     * @param SettingPassword $entity
     */
    public function saveWithSystemModifier(SettingPassword $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingPassword entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingPassword $entity
     */
    public function forceSave(SettingPassword $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingPassword entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingPassword $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingPassword $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingPassword entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingPassword $entity
     */
    public function delete(SettingPassword $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingPassword entity on behalf of respective modifier user.
     * @param SettingPassword $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingPassword $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingPassword entity on behalf of system user.
     * @param SettingPassword $entity
     */
    public function deleteWithSystemModifier(SettingPassword $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
