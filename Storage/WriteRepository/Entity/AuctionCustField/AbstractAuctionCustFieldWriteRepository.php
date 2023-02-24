<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionCustField;

use AuctionCustField;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionCustFieldWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionCustField entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionCustField $entity
     */
    public function save(AuctionCustField $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionCustField entity in DB on behalf of respective modifier user.
     * @param AuctionCustField $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionCustField $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionCustField entity in DB on behalf of system user.
     * @param AuctionCustField $entity
     */
    public function saveWithSystemModifier(AuctionCustField $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionCustField entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionCustField $entity
     */
    public function forceSave(AuctionCustField $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionCustField entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionCustField $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionCustField $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionCustField entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionCustField $entity
     */
    public function delete(AuctionCustField $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionCustField entity on behalf of respective modifier user.
     * @param AuctionCustField $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionCustField $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionCustField entity on behalf of system user.
     * @param AuctionCustField $entity
     */
    public function deleteWithSystemModifier(AuctionCustField $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
