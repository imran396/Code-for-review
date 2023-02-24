<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionBidder;

use AuctionBidder;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionBidderWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionBidder entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionBidder $entity
     */
    public function save(AuctionBidder $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionBidder entity in DB on behalf of respective modifier user.
     * @param AuctionBidder $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionBidder $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionBidder entity in DB on behalf of system user.
     * @param AuctionBidder $entity
     */
    public function saveWithSystemModifier(AuctionBidder $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionBidder entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionBidder $entity
     */
    public function forceSave(AuctionBidder $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionBidder entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionBidder $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionBidder $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionBidder entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionBidder $entity
     */
    public function delete(AuctionBidder $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionBidder entity on behalf of respective modifier user.
     * @param AuctionBidder $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionBidder $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionBidder entity on behalf of system user.
     * @param AuctionBidder $entity
     */
    public function deleteWithSystemModifier(AuctionBidder $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
