<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BidIncrement;

use BidIncrement;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractBidIncrementWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist BidIncrement entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param BidIncrement $entity
     */
    public function save(BidIncrement $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist BidIncrement entity in DB on behalf of respective modifier user.
     * @param BidIncrement $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(BidIncrement $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist BidIncrement entity in DB on behalf of system user.
     * @param BidIncrement $entity
     */
    public function saveWithSystemModifier(BidIncrement $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist BidIncrement entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BidIncrement $entity
     */
    public function forceSave(BidIncrement $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist BidIncrement entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BidIncrement $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(BidIncrement $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BidIncrement entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param BidIncrement $entity
     */
    public function delete(BidIncrement $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete BidIncrement entity on behalf of respective modifier user.
     * @param BidIncrement $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(BidIncrement $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BidIncrement entity on behalf of system user.
     * @param BidIncrement $entity
     */
    public function deleteWithSystemModifier(BidIncrement $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
