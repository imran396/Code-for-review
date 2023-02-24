<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BuyerGroup;

use BuyerGroup;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractBuyerGroupWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist BuyerGroup entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param BuyerGroup $entity
     */
    public function save(BuyerGroup $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist BuyerGroup entity in DB on behalf of respective modifier user.
     * @param BuyerGroup $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(BuyerGroup $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist BuyerGroup entity in DB on behalf of system user.
     * @param BuyerGroup $entity
     */
    public function saveWithSystemModifier(BuyerGroup $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist BuyerGroup entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BuyerGroup $entity
     */
    public function forceSave(BuyerGroup $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist BuyerGroup entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BuyerGroup $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(BuyerGroup $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BuyerGroup entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param BuyerGroup $entity
     */
    public function delete(BuyerGroup $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete BuyerGroup entity on behalf of respective modifier user.
     * @param BuyerGroup $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(BuyerGroup $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BuyerGroup entity on behalf of system user.
     * @param BuyerGroup $entity
     */
    public function deleteWithSystemModifier(BuyerGroup $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
