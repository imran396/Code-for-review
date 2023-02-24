<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingAuction;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingAuction;

abstract class AbstractSettingAuctionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingAuction entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingAuction $entity
     */
    public function save(SettingAuction $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingAuction entity in DB on behalf of respective modifier user.
     * @param SettingAuction $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingAuction $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingAuction entity in DB on behalf of system user.
     * @param SettingAuction $entity
     */
    public function saveWithSystemModifier(SettingAuction $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingAuction entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingAuction $entity
     */
    public function forceSave(SettingAuction $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingAuction entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingAuction $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingAuction $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingAuction entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingAuction $entity
     */
    public function delete(SettingAuction $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingAuction entity on behalf of respective modifier user.
     * @param SettingAuction $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingAuction $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingAuction entity on behalf of system user.
     * @param SettingAuction $entity
     */
    public function deleteWithSystemModifier(SettingAuction $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
