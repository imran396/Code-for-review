<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettlementAdditional;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettlementAdditional;

abstract class AbstractSettlementAdditionalWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettlementAdditional entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettlementAdditional $entity
     */
    public function save(SettlementAdditional $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettlementAdditional entity in DB on behalf of respective modifier user.
     * @param SettlementAdditional $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettlementAdditional $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettlementAdditional entity in DB on behalf of system user.
     * @param SettlementAdditional $entity
     */
    public function saveWithSystemModifier(SettlementAdditional $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettlementAdditional entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettlementAdditional $entity
     */
    public function forceSave(SettlementAdditional $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettlementAdditional entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettlementAdditional $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettlementAdditional $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettlementAdditional entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettlementAdditional $entity
     */
    public function delete(SettlementAdditional $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettlementAdditional entity on behalf of respective modifier user.
     * @param SettlementAdditional $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettlementAdditional $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettlementAdditional entity on behalf of system user.
     * @param SettlementAdditional $entity
     */
    public function deleteWithSystemModifier(SettlementAdditional $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
