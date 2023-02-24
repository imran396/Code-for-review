<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionRtbd;

use AuctionRtbd;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionRtbdWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionRtbd entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionRtbd $entity
     */
    public function save(AuctionRtbd $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionRtbd entity in DB on behalf of respective modifier user.
     * @param AuctionRtbd $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionRtbd $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionRtbd entity in DB on behalf of system user.
     * @param AuctionRtbd $entity
     */
    public function saveWithSystemModifier(AuctionRtbd $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionRtbd entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionRtbd $entity
     */
    public function forceSave(AuctionRtbd $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionRtbd entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionRtbd $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionRtbd $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionRtbd entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionRtbd $entity
     */
    public function delete(AuctionRtbd $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionRtbd entity on behalf of respective modifier user.
     * @param AuctionRtbd $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionRtbd $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionRtbd entity on behalf of system user.
     * @param AuctionRtbd $entity
     */
    public function deleteWithSystemModifier(AuctionRtbd $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
