<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingAccessPermission;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingAccessPermission;

abstract class AbstractSettingAccessPermissionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingAccessPermission entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingAccessPermission $entity
     */
    public function save(SettingAccessPermission $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingAccessPermission entity in DB on behalf of respective modifier user.
     * @param SettingAccessPermission $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingAccessPermission $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingAccessPermission entity in DB on behalf of system user.
     * @param SettingAccessPermission $entity
     */
    public function saveWithSystemModifier(SettingAccessPermission $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingAccessPermission entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingAccessPermission $entity
     */
    public function forceSave(SettingAccessPermission $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingAccessPermission entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingAccessPermission $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingAccessPermission $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingAccessPermission entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingAccessPermission $entity
     */
    public function delete(SettingAccessPermission $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingAccessPermission entity on behalf of respective modifier user.
     * @param SettingAccessPermission $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingAccessPermission $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingAccessPermission entity on behalf of system user.
     * @param SettingAccessPermission $entity
     */
    public function deleteWithSystemModifier(SettingAccessPermission $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
