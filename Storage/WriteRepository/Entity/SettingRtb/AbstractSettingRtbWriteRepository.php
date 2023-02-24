<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingRtb;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingRtb;

abstract class AbstractSettingRtbWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingRtb entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingRtb $entity
     */
    public function save(SettingRtb $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingRtb entity in DB on behalf of respective modifier user.
     * @param SettingRtb $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingRtb $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingRtb entity in DB on behalf of system user.
     * @param SettingRtb $entity
     */
    public function saveWithSystemModifier(SettingRtb $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingRtb entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingRtb $entity
     */
    public function forceSave(SettingRtb $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingRtb entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingRtb $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingRtb $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingRtb entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingRtb $entity
     */
    public function delete(SettingRtb $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingRtb entity on behalf of respective modifier user.
     * @param SettingRtb $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingRtb $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingRtb entity on behalf of system user.
     * @param SettingRtb $entity
     */
    public function deleteWithSystemModifier(SettingRtb $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
