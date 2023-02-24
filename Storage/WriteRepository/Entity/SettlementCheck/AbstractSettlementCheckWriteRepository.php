<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettlementCheck;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettlementCheck;

abstract class AbstractSettlementCheckWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettlementCheck entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettlementCheck $entity
     */
    public function save(SettlementCheck $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettlementCheck entity in DB on behalf of respective modifier user.
     * @param SettlementCheck $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettlementCheck $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettlementCheck entity in DB on behalf of system user.
     * @param SettlementCheck $entity
     */
    public function saveWithSystemModifier(SettlementCheck $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettlementCheck entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettlementCheck $entity
     */
    public function forceSave(SettlementCheck $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettlementCheck entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettlementCheck $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettlementCheck $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettlementCheck entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettlementCheck $entity
     */
    public function delete(SettlementCheck $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettlementCheck entity on behalf of respective modifier user.
     * @param SettlementCheck $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettlementCheck $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettlementCheck entity on behalf of system user.
     * @param SettlementCheck $entity
     */
    public function deleteWithSystemModifier(SettlementCheck $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
