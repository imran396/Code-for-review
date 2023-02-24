<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSettlement;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingSettlement;

abstract class AbstractSettingSettlementWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingSettlement entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingSettlement $entity
     */
    public function save(SettingSettlement $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingSettlement entity in DB on behalf of respective modifier user.
     * @param SettingSettlement $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingSettlement $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingSettlement entity in DB on behalf of system user.
     * @param SettingSettlement $entity
     */
    public function saveWithSystemModifier(SettingSettlement $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingSettlement entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSettlement $entity
     */
    public function forceSave(SettingSettlement $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingSettlement entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSettlement $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingSettlement $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSettlement entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingSettlement $entity
     */
    public function delete(SettingSettlement $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingSettlement entity on behalf of respective modifier user.
     * @param SettingSettlement $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingSettlement $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSettlement entity on behalf of system user.
     * @param SettingSettlement $entity
     */
    public function deleteWithSystemModifier(SettingSettlement $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
