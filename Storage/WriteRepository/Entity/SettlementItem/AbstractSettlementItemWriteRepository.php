<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettlementItem;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettlementItem;

abstract class AbstractSettlementItemWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettlementItem entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettlementItem $entity
     */
    public function save(SettlementItem $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettlementItem entity in DB on behalf of respective modifier user.
     * @param SettlementItem $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettlementItem $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettlementItem entity in DB on behalf of system user.
     * @param SettlementItem $entity
     */
    public function saveWithSystemModifier(SettlementItem $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettlementItem entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettlementItem $entity
     */
    public function forceSave(SettlementItem $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettlementItem entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettlementItem $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettlementItem $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettlementItem entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettlementItem $entity
     */
    public function delete(SettlementItem $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettlementItem entity on behalf of respective modifier user.
     * @param SettlementItem $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettlementItem $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettlementItem entity on behalf of system user.
     * @param SettlementItem $entity
     */
    public function deleteWithSystemModifier(SettlementItem $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
