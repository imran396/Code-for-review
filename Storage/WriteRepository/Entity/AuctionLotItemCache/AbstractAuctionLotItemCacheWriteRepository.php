<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionLotItemCache;

use AuctionLotItemCache;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionLotItemCacheWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionLotItemCache entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionLotItemCache $entity
     */
    public function save(AuctionLotItemCache $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionLotItemCache entity in DB on behalf of respective modifier user.
     * @param AuctionLotItemCache $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionLotItemCache $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionLotItemCache entity in DB on behalf of system user.
     * @param AuctionLotItemCache $entity
     */
    public function saveWithSystemModifier(AuctionLotItemCache $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionLotItemCache entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionLotItemCache $entity
     */
    public function forceSave(AuctionLotItemCache $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionLotItemCache entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionLotItemCache $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionLotItemCache $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionLotItemCache entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionLotItemCache $entity
     */
    public function delete(AuctionLotItemCache $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionLotItemCache entity on behalf of respective modifier user.
     * @param AuctionLotItemCache $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionLotItemCache $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionLotItemCache entity on behalf of system user.
     * @param AuctionLotItemCache $entity
     */
    public function deleteWithSystemModifier(AuctionLotItemCache $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
