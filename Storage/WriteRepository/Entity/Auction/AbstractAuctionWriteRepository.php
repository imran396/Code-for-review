<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Auction;

use Auction;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Auction entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Auction $entity
     */
    public function save(Auction $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Auction entity in DB on behalf of respective modifier user.
     * @param Auction $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Auction $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Auction entity in DB on behalf of system user.
     * @param Auction $entity
     */
    public function saveWithSystemModifier(Auction $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Auction entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Auction $entity
     */
    public function forceSave(Auction $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Auction entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Auction $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Auction $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Auction entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Auction $entity
     */
    public function delete(Auction $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Auction entity on behalf of respective modifier user.
     * @param Auction $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Auction $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Auction entity on behalf of system user.
     * @param Auction $entity
     */
    public function deleteWithSystemModifier(Auction $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
