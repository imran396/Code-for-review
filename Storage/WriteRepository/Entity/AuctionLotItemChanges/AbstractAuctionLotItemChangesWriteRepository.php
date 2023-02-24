<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionLotItemChanges;

use AuctionLotItemChanges;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionLotItemChangesWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionLotItemChanges entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionLotItemChanges $entity
     */
    public function save(AuctionLotItemChanges $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionLotItemChanges entity in DB on behalf of respective modifier user.
     * @param AuctionLotItemChanges $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionLotItemChanges $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionLotItemChanges entity in DB on behalf of system user.
     * @param AuctionLotItemChanges $entity
     */
    public function saveWithSystemModifier(AuctionLotItemChanges $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionLotItemChanges entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionLotItemChanges $entity
     */
    public function forceSave(AuctionLotItemChanges $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionLotItemChanges entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionLotItemChanges $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionLotItemChanges $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionLotItemChanges entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionLotItemChanges $entity
     */
    public function delete(AuctionLotItemChanges $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionLotItemChanges entity on behalf of respective modifier user.
     * @param AuctionLotItemChanges $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionLotItemChanges $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionLotItemChanges entity on behalf of system user.
     * @param AuctionLotItemChanges $entity
     */
    public function deleteWithSystemModifier(AuctionLotItemChanges $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
