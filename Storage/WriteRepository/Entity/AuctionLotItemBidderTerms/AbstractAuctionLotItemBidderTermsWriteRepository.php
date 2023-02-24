<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionLotItemBidderTerms;

use AuctionLotItemBidderTerms;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionLotItemBidderTermsWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionLotItemBidderTerms entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionLotItemBidderTerms $entity
     */
    public function save(AuctionLotItemBidderTerms $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionLotItemBidderTerms entity in DB on behalf of respective modifier user.
     * @param AuctionLotItemBidderTerms $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionLotItemBidderTerms $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionLotItemBidderTerms entity in DB on behalf of system user.
     * @param AuctionLotItemBidderTerms $entity
     */
    public function saveWithSystemModifier(AuctionLotItemBidderTerms $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionLotItemBidderTerms entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionLotItemBidderTerms $entity
     */
    public function forceSave(AuctionLotItemBidderTerms $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionLotItemBidderTerms entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionLotItemBidderTerms $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionLotItemBidderTerms $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionLotItemBidderTerms entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionLotItemBidderTerms $entity
     */
    public function delete(AuctionLotItemBidderTerms $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionLotItemBidderTerms entity on behalf of respective modifier user.
     * @param AuctionLotItemBidderTerms $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionLotItemBidderTerms $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionLotItemBidderTerms entity on behalf of system user.
     * @param AuctionLotItemBidderTerms $entity
     */
    public function deleteWithSystemModifier(AuctionLotItemBidderTerms $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
