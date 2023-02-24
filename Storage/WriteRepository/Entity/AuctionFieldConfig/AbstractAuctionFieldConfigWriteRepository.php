<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionFieldConfig;

use AuctionFieldConfig;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionFieldConfigWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionFieldConfig entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionFieldConfig $entity
     */
    public function save(AuctionFieldConfig $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionFieldConfig entity in DB on behalf of respective modifier user.
     * @param AuctionFieldConfig $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionFieldConfig $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionFieldConfig entity in DB on behalf of system user.
     * @param AuctionFieldConfig $entity
     */
    public function saveWithSystemModifier(AuctionFieldConfig $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionFieldConfig entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionFieldConfig $entity
     */
    public function forceSave(AuctionFieldConfig $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionFieldConfig entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionFieldConfig $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionFieldConfig $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionFieldConfig entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionFieldConfig $entity
     */
    public function delete(AuctionFieldConfig $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionFieldConfig entity on behalf of respective modifier user.
     * @param AuctionFieldConfig $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionFieldConfig $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionFieldConfig entity on behalf of system user.
     * @param AuctionFieldConfig $entity
     */
    public function deleteWithSystemModifier(AuctionFieldConfig $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
