<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingUser;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingUser;

abstract class AbstractSettingUserWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingUser entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingUser $entity
     */
    public function save(SettingUser $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingUser entity in DB on behalf of respective modifier user.
     * @param SettingUser $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingUser $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingUser entity in DB on behalf of system user.
     * @param SettingUser $entity
     */
    public function saveWithSystemModifier(SettingUser $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingUser entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingUser $entity
     */
    public function forceSave(SettingUser $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingUser entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingUser $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingUser $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingUser entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingUser $entity
     */
    public function delete(SettingUser $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingUser entity on behalf of respective modifier user.
     * @param SettingUser $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingUser $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingUser entity on behalf of system user.
     * @param SettingUser $entity
     */
    public function deleteWithSystemModifier(SettingUser $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
