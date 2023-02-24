<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BidTransaction;

use BidTransaction;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractBidTransactionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist BidTransaction entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param BidTransaction $entity
     */
    public function save(BidTransaction $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist BidTransaction entity in DB on behalf of respective modifier user.
     * @param BidTransaction $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(BidTransaction $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist BidTransaction entity in DB on behalf of system user.
     * @param BidTransaction $entity
     */
    public function saveWithSystemModifier(BidTransaction $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist BidTransaction entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BidTransaction $entity
     */
    public function forceSave(BidTransaction $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist BidTransaction entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BidTransaction $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(BidTransaction $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BidTransaction entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param BidTransaction $entity
     */
    public function delete(BidTransaction $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete BidTransaction entity on behalf of respective modifier user.
     * @param BidTransaction $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(BidTransaction $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BidTransaction entity on behalf of system user.
     * @param BidTransaction $entity
     */
    public function deleteWithSystemModifier(BidTransaction $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
