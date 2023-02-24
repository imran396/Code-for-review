<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSettlementCheck;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingSettlementCheck;

abstract class AbstractSettingSettlementCheckWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingSettlementCheck entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingSettlementCheck $entity
     */
    public function save(SettingSettlementCheck $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingSettlementCheck entity in DB on behalf of respective modifier user.
     * @param SettingSettlementCheck $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingSettlementCheck $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingSettlementCheck entity in DB on behalf of system user.
     * @param SettingSettlementCheck $entity
     */
    public function saveWithSystemModifier(SettingSettlementCheck $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingSettlementCheck entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSettlementCheck $entity
     */
    public function forceSave(SettingSettlementCheck $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingSettlementCheck entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSettlementCheck $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingSettlementCheck $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSettlementCheck entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingSettlementCheck $entity
     */
    public function delete(SettingSettlementCheck $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingSettlementCheck entity on behalf of respective modifier user.
     * @param SettingSettlementCheck $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingSettlementCheck $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSettlementCheck entity on behalf of system user.
     * @param SettingSettlementCheck $entity
     */
    public function deleteWithSystemModifier(SettingSettlementCheck $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
