<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionincStats;

use AuctionincStats;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionincStatsWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionincStats entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionincStats $entity
     */
    public function save(AuctionincStats $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionincStats entity in DB on behalf of respective modifier user.
     * @param AuctionincStats $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionincStats $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionincStats entity in DB on behalf of system user.
     * @param AuctionincStats $entity
     */
    public function saveWithSystemModifier(AuctionincStats $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionincStats entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionincStats $entity
     */
    public function forceSave(AuctionincStats $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionincStats entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionincStats $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionincStats $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionincStats entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionincStats $entity
     */
    public function delete(AuctionincStats $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionincStats entity on behalf of respective modifier user.
     * @param AuctionincStats $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionincStats $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionincStats entity on behalf of system user.
     * @param AuctionincStats $entity
     */
    public function deleteWithSystemModifier(AuctionincStats $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
