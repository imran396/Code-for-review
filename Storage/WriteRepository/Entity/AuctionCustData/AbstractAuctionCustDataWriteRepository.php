<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionCustData;

use AuctionCustData;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionCustDataWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionCustData entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionCustData $entity
     */
    public function save(AuctionCustData $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionCustData entity in DB on behalf of respective modifier user.
     * @param AuctionCustData $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionCustData $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionCustData entity in DB on behalf of system user.
     * @param AuctionCustData $entity
     */
    public function saveWithSystemModifier(AuctionCustData $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionCustData entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionCustData $entity
     */
    public function forceSave(AuctionCustData $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionCustData entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionCustData $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionCustData $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionCustData entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionCustData $entity
     */
    public function delete(AuctionCustData $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionCustData entity on behalf of respective modifier user.
     * @param AuctionCustData $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionCustData $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionCustData entity on behalf of system user.
     * @param AuctionCustData $entity
     */
    public function deleteWithSystemModifier(AuctionCustData $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
