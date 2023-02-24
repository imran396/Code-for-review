<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TimedOnlineOfferBid;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use TimedOnlineOfferBid;

abstract class AbstractTimedOnlineOfferBidWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist TimedOnlineOfferBid entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param TimedOnlineOfferBid $entity
     */
    public function save(TimedOnlineOfferBid $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist TimedOnlineOfferBid entity in DB on behalf of respective modifier user.
     * @param TimedOnlineOfferBid $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(TimedOnlineOfferBid $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist TimedOnlineOfferBid entity in DB on behalf of system user.
     * @param TimedOnlineOfferBid $entity
     */
    public function saveWithSystemModifier(TimedOnlineOfferBid $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist TimedOnlineOfferBid entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TimedOnlineOfferBid $entity
     */
    public function forceSave(TimedOnlineOfferBid $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist TimedOnlineOfferBid entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TimedOnlineOfferBid $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(TimedOnlineOfferBid $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TimedOnlineOfferBid entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param TimedOnlineOfferBid $entity
     */
    public function delete(TimedOnlineOfferBid $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete TimedOnlineOfferBid entity on behalf of respective modifier user.
     * @param TimedOnlineOfferBid $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(TimedOnlineOfferBid $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TimedOnlineOfferBid entity on behalf of system user.
     * @param TimedOnlineOfferBid $entity
     */
    public function deleteWithSystemModifier(TimedOnlineOfferBid $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
