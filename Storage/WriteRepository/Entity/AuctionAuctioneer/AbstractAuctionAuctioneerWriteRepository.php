<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionAuctioneer;

use AuctionAuctioneer;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionAuctioneerWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionAuctioneer entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionAuctioneer $entity
     */
    public function save(AuctionAuctioneer $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionAuctioneer entity in DB on behalf of respective modifier user.
     * @param AuctionAuctioneer $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionAuctioneer $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionAuctioneer entity in DB on behalf of system user.
     * @param AuctionAuctioneer $entity
     */
    public function saveWithSystemModifier(AuctionAuctioneer $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionAuctioneer entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionAuctioneer $entity
     */
    public function forceSave(AuctionAuctioneer $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionAuctioneer entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionAuctioneer $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionAuctioneer $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionAuctioneer entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionAuctioneer $entity
     */
    public function delete(AuctionAuctioneer $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionAuctioneer entity on behalf of respective modifier user.
     * @param AuctionAuctioneer $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionAuctioneer $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionAuctioneer entity on behalf of system user.
     * @param AuctionAuctioneer $entity
     */
    public function deleteWithSystemModifier(AuctionAuctioneer $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
