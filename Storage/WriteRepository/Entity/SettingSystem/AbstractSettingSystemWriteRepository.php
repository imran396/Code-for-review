<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSystem;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingSystem;

abstract class AbstractSettingSystemWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingSystem entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingSystem $entity
     */
    public function save(SettingSystem $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingSystem entity in DB on behalf of respective modifier user.
     * @param SettingSystem $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingSystem $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingSystem entity in DB on behalf of system user.
     * @param SettingSystem $entity
     */
    public function saveWithSystemModifier(SettingSystem $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingSystem entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSystem $entity
     */
    public function forceSave(SettingSystem $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingSystem entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSystem $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingSystem $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSystem entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingSystem $entity
     */
    public function delete(SettingSystem $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingSystem entity on behalf of respective modifier user.
     * @param SettingSystem $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingSystem $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSystem entity on behalf of system user.
     * @param SettingSystem $entity
     */
    public function deleteWithSystemModifier(SettingSystem $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
