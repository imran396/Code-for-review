<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionCurrency;

use AuctionCurrency;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionCurrencyWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionCurrency entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionCurrency $entity
     */
    public function save(AuctionCurrency $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionCurrency entity in DB on behalf of respective modifier user.
     * @param AuctionCurrency $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionCurrency $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionCurrency entity in DB on behalf of system user.
     * @param AuctionCurrency $entity
     */
    public function saveWithSystemModifier(AuctionCurrency $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionCurrency entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionCurrency $entity
     */
    public function forceSave(AuctionCurrency $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionCurrency entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionCurrency $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionCurrency $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionCurrency entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionCurrency $entity
     */
    public function delete(AuctionCurrency $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionCurrency entity on behalf of respective modifier user.
     * @param AuctionCurrency $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionCurrency $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionCurrency entity on behalf of system user.
     * @param AuctionCurrency $entity
     */
    public function deleteWithSystemModifier(AuctionCurrency $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
