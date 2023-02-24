<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Bidder;

use Bidder;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractBidderWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Bidder entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Bidder $entity
     */
    public function save(Bidder $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Bidder entity in DB on behalf of respective modifier user.
     * @param Bidder $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Bidder $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Bidder entity in DB on behalf of system user.
     * @param Bidder $entity
     */
    public function saveWithSystemModifier(Bidder $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Bidder entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Bidder $entity
     */
    public function forceSave(Bidder $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Bidder entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Bidder $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Bidder $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Bidder entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Bidder $entity
     */
    public function delete(Bidder $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Bidder entity on behalf of respective modifier user.
     * @param Bidder $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Bidder $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Bidder entity on behalf of system user.
     * @param Bidder $entity
     */
    public function deleteWithSystemModifier(Bidder $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
