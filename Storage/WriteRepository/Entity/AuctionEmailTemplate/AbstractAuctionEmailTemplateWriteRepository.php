<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionEmailTemplate;

use AuctionEmailTemplate;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuctionEmailTemplateWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuctionEmailTemplate entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuctionEmailTemplate $entity
     */
    public function save(AuctionEmailTemplate $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuctionEmailTemplate entity in DB on behalf of respective modifier user.
     * @param AuctionEmailTemplate $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuctionEmailTemplate $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuctionEmailTemplate entity in DB on behalf of system user.
     * @param AuctionEmailTemplate $entity
     */
    public function saveWithSystemModifier(AuctionEmailTemplate $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuctionEmailTemplate entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionEmailTemplate $entity
     */
    public function forceSave(AuctionEmailTemplate $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuctionEmailTemplate entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuctionEmailTemplate $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuctionEmailTemplate $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionEmailTemplate entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuctionEmailTemplate $entity
     */
    public function delete(AuctionEmailTemplate $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuctionEmailTemplate entity on behalf of respective modifier user.
     * @param AuctionEmailTemplate $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuctionEmailTemplate $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuctionEmailTemplate entity on behalf of system user.
     * @param AuctionEmailTemplate $entity
     */
    public function deleteWithSystemModifier(AuctionEmailTemplate $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
