<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionImage;

use AuctionImage;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionImageWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionImage entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionImage $entity
     */
    public function save(AuctionImage $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionImage entity in DB on behalf of respective modifier user.
     * @param AuctionImage $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionImage $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionImage entity in DB on behalf of system user.
     * @param AuctionImage $entity
     */
    public function saveWithSystemModifier(AuctionImage $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionImage entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionImage $entity
     */
    public function forceSave(AuctionImage $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionImage entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionImage $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionImage $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionImage entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionImage $entity
     */
    public function delete(AuctionImage $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionImage entity on behalf of respective modifier user.
     * @param AuctionImage $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionImage $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionImage entity on behalf of system user.
     * @param AuctionImage $entity
     */
    public function deleteWithSystemModifier(AuctionImage $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
