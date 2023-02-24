<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionDynamic;

use AuctionDynamic;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionDynamicWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionDynamic entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionDynamic $entity
     */
    public function save(AuctionDynamic $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionDynamic entity in DB on behalf of respective modifier user.
     * @param AuctionDynamic $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionDynamic $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionDynamic entity in DB on behalf of system user.
     * @param AuctionDynamic $entity
     */
    public function saveWithSystemModifier(AuctionDynamic $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionDynamic entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionDynamic $entity
     */
    public function forceSave(AuctionDynamic $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionDynamic entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionDynamic $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionDynamic $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionDynamic entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionDynamic $entity
     */
    public function delete(AuctionDynamic $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionDynamic entity on behalf of respective modifier user.
     * @param AuctionDynamic $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionDynamic $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionDynamic entity on behalf of system user.
     * @param AuctionDynamic $entity
     */
    public function deleteWithSystemModifier(AuctionDynamic $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
