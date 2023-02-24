<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionLotItem;

use AuctionLotItem;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionLotItemWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionLotItem entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionLotItem $entity
     */
    public function save(AuctionLotItem $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionLotItem entity in DB on behalf of respective modifier user.
     * @param AuctionLotItem $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionLotItem $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionLotItem entity in DB on behalf of system user.
     * @param AuctionLotItem $entity
     */
    public function saveWithSystemModifier(AuctionLotItem $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionLotItem entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionLotItem $entity
     */
    public function forceSave(AuctionLotItem $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionLotItem entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionLotItem $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionLotItem $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionLotItem entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionLotItem $entity
     */
    public function delete(AuctionLotItem $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionLotItem entity on behalf of respective modifier user.
     * @param AuctionLotItem $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionLotItem $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionLotItem entity on behalf of system user.
     * @param AuctionLotItem $entity
     */
    public function deleteWithSystemModifier(AuctionLotItem $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
