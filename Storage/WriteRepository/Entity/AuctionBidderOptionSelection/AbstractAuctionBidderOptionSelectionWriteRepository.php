<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionBidderOptionSelection;

use AuctionBidderOptionSelection;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionBidderOptionSelectionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionBidderOptionSelection entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionBidderOptionSelection $entity
     */
    public function save(AuctionBidderOptionSelection $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionBidderOptionSelection entity in DB on behalf of respective modifier user.
     * @param AuctionBidderOptionSelection $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionBidderOptionSelection $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionBidderOptionSelection entity in DB on behalf of system user.
     * @param AuctionBidderOptionSelection $entity
     */
    public function saveWithSystemModifier(AuctionBidderOptionSelection $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionBidderOptionSelection entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionBidderOptionSelection $entity
     */
    public function forceSave(AuctionBidderOptionSelection $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionBidderOptionSelection entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionBidderOptionSelection $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionBidderOptionSelection $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionBidderOptionSelection entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionBidderOptionSelection $entity
     */
    public function delete(AuctionBidderOptionSelection $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionBidderOptionSelection entity on behalf of respective modifier user.
     * @param AuctionBidderOptionSelection $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionBidderOptionSelection $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionBidderOptionSelection entity on behalf of system user.
     * @param AuctionBidderOptionSelection $entity
     */
    public function deleteWithSystemModifier(AuctionBidderOptionSelection $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
