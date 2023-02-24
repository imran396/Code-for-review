<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSmtp;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingSmtp;

abstract class AbstractSettingSmtpWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingSmtp entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingSmtp $entity
     */
    public function save(SettingSmtp $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingSmtp entity in DB on behalf of respective modifier user.
     * @param SettingSmtp $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingSmtp $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingSmtp entity in DB on behalf of system user.
     * @param SettingSmtp $entity
     */
    public function saveWithSystemModifier(SettingSmtp $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingSmtp entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSmtp $entity
     */
    public function forceSave(SettingSmtp $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingSmtp entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSmtp $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingSmtp $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSmtp entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingSmtp $entity
     */
    public function delete(SettingSmtp $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingSmtp entity on behalf of respective modifier user.
     * @param SettingSmtp $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingSmtp $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSmtp entity on behalf of system user.
     * @param SettingSmtp $entity
     */
    public function deleteWithSystemModifier(SettingSmtp $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
