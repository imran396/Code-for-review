<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionCache;

use AuctionCache;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionCacheWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionCache entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionCache $entity
     */
    public function save(AuctionCache $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionCache entity in DB on behalf of respective modifier user.
     * @param AuctionCache $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionCache $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionCache entity in DB on behalf of system user.
     * @param AuctionCache $entity
     */
    public function saveWithSystemModifier(AuctionCache $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionCache entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionCache $entity
     */
    public function forceSave(AuctionCache $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionCache entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionCache $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionCache $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionCache entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionCache $entity
     */
    public function delete(AuctionCache $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionCache entity on behalf of respective modifier user.
     * @param AuctionCache $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionCache $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionCache entity on behalf of system user.
     * @param AuctionCache $entity
     */
    public function deleteWithSystemModifier(AuctionCache $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
