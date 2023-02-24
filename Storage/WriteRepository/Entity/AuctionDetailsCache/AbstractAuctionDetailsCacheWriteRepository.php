<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionDetailsCache;

use AuctionDetailsCache;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionDetailsCacheWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionDetailsCache entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionDetailsCache $entity
     */
    public function save(AuctionDetailsCache $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionDetailsCache entity in DB on behalf of respective modifier user.
     * @param AuctionDetailsCache $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionDetailsCache $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionDetailsCache entity in DB on behalf of system user.
     * @param AuctionDetailsCache $entity
     */
    public function saveWithSystemModifier(AuctionDetailsCache $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionDetailsCache entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionDetailsCache $entity
     */
    public function forceSave(AuctionDetailsCache $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionDetailsCache entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionDetailsCache $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionDetailsCache $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionDetailsCache entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionDetailsCache $entity
     */
    public function delete(AuctionDetailsCache $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionDetailsCache entity on behalf of respective modifier user.
     * @param AuctionDetailsCache $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionDetailsCache $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionDetailsCache entity on behalf of system user.
     * @param AuctionDetailsCache $entity
     */
    public function deleteWithSystemModifier(AuctionDetailsCache $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
