<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BuyerGroupUser;

use BuyerGroupUser;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractBuyerGroupUserWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist BuyerGroupUser entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param BuyerGroupUser $entity
     */
    public function save(BuyerGroupUser $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist BuyerGroupUser entity in DB on behalf of respective modifier user.
     * @param BuyerGroupUser $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(BuyerGroupUser $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist BuyerGroupUser entity in DB on behalf of system user.
     * @param BuyerGroupUser $entity
     */
    public function saveWithSystemModifier(BuyerGroupUser $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist BuyerGroupUser entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BuyerGroupUser $entity
     */
    public function forceSave(BuyerGroupUser $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist BuyerGroupUser entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BuyerGroupUser $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(BuyerGroupUser $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BuyerGroupUser entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param BuyerGroupUser $entity
     */
    public function delete(BuyerGroupUser $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete BuyerGroupUser entity on behalf of respective modifier user.
     * @param BuyerGroupUser $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(BuyerGroupUser $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BuyerGroupUser entity on behalf of system user.
     * @param BuyerGroupUser $entity
     */
    public function deleteWithSystemModifier(BuyerGroupUser $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
