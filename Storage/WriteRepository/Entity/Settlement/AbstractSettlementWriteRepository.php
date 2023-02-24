<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Settlement;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use Settlement;

abstract class AbstractSettlementWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Settlement entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Settlement $entity
     */
    public function save(Settlement $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Settlement entity in DB on behalf of respective modifier user.
     * @param Settlement $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Settlement $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Settlement entity in DB on behalf of system user.
     * @param Settlement $entity
     */
    public function saveWithSystemModifier(Settlement $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Settlement entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Settlement $entity
     */
    public function forceSave(Settlement $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Settlement entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Settlement $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Settlement $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Settlement entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Settlement $entity
     */
    public function delete(Settlement $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Settlement entity on behalf of respective modifier user.
     * @param Settlement $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Settlement $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Settlement entity on behalf of system user.
     * @param Settlement $entity
     */
    public function deleteWithSystemModifier(Settlement $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
