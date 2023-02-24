<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionBidderOption;

use AuctionBidderOption;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionBidderOptionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionBidderOption entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionBidderOption $entity
     */
    public function save(AuctionBidderOption $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionBidderOption entity in DB on behalf of respective modifier user.
     * @param AuctionBidderOption $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionBidderOption $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionBidderOption entity in DB on behalf of system user.
     * @param AuctionBidderOption $entity
     */
    public function saveWithSystemModifier(AuctionBidderOption $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionBidderOption entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionBidderOption $entity
     */
    public function forceSave(AuctionBidderOption $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionBidderOption entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionBidderOption $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionBidderOption $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionBidderOption entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionBidderOption $entity
     */
    public function delete(AuctionBidderOption $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionBidderOption entity on behalf of respective modifier user.
     * @param AuctionBidderOption $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionBidderOption $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionBidderOption entity on behalf of system user.
     * @param AuctionBidderOption $entity
     */
    public function deleteWithSystemModifier(AuctionBidderOption $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
