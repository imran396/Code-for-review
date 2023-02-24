<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingUi;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingUi;

abstract class AbstractSettingUiWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingUi entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingUi $entity
     */
    public function save(SettingUi $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingUi entity in DB on behalf of respective modifier user.
     * @param SettingUi $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingUi $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingUi entity in DB on behalf of system user.
     * @param SettingUi $entity
     */
    public function saveWithSystemModifier(SettingUi $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingUi entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingUi $entity
     */
    public function forceSave(SettingUi $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingUi entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingUi $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingUi $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingUi entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingUi $entity
     */
    public function delete(SettingUi $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingUi entity on behalf of respective modifier user.
     * @param SettingUi $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingUi $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingUi entity on behalf of system user.
     * @param SettingUi $entity
     */
    public function deleteWithSystemModifier(SettingUi $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
